<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Pure-PHP high-fidelity DOCX -> PDF converter powered by DomPDF.
 *
 * Preserves the exact uploaded Word document formatting without adding or
 * duplicating journal headers, titles, or abstracts:
 *  - Exact running headers (including embedded logos & accent borders)
 *  - Exact running footers with dynamic "Page X of Y" numbering
 *  - Exact styles from word/styles.xml (inheritance, fonts, sizes, colors, spacing, indents)
 *  - Exact tables (borders, cell shading, cell padding, colspans, rowspans, column widths)
 *  - Exact inline formatting (bold, italic, underline, strike, superscript, subscript, shading/badges, links)
 *  - Exact embedded images & figures (from word/media/*)
 *  - Exact list numbering & bullets (from word/numbering.xml)
 *  - Single-column and balanced two-column section layouts (matching Word <w:sectPr><w:cols>)
 *  - VML diagonal watermarks if present in header XML
 */
class DocxToPdfService
{
    protected const RENDERER_VERSION = 'v2';

    protected const W_NS = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    protected const R_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    protected const A_NS = 'http://schemas.openxmlformats.org/drawingml/2006/main';
    protected const WP_NS = 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing';
    protected const V_NS = 'urn:schemas-microsoft-com:vml';
    protected const M_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/math';

    protected \ZipArchive $zip;
    protected array $styles = [];
    protected array $docDefaultRPr = [];
    protected array $docDefaultPPr = [];
    protected array $numbering = [];
    protected array $listCounters = [];
    protected array $mediaCache = [];
    protected bool $useUnicodeFonts = false;
    protected bool $footerHasPageNumbers = false;
    protected string $watermarkText = '';

    /**
     * Convert a .docx file on disk (or raw .docx binary bytes) to a cached PDF file path.
     * Returns the absolute path to the generated PDF on success, or null on failure.
     */
    public static function convert(string $sourcePathOrBytes, bool $isBinary = false): ?string
    {
        if (!class_exists(Dompdf::class) || !class_exists(\ZipArchive::class)) {
            return null;
        }

        $tempDocx = null;
        try {
            if ($isBinary) {
                if (empty($sourcePathOrBytes) || !str_starts_with($sourcePathOrBytes, "PK")) {
                    return null;
                }
                $hash = md5($sourcePathOrBytes);
                $tempDocx = sys_get_temp_dir() . '/hjparam_docx_' . $hash . '_' . Str::random(4) . '.docx';
                file_put_contents($tempDocx, $sourcePathOrBytes);
                $docxPath = $tempDocx;
            } else {
                if (empty($sourcePathOrBytes) || !file_exists($sourcePathOrBytes) || is_dir($sourcePathOrBytes)) {
                    return null;
                }
                $h = @fopen($sourcePathOrBytes, 'rb');
                $sig = $h ? fread($h, 2) : '';
                if ($h) fclose($h);
                if ($sig !== 'PK') {
                    return null;
                }
                $docxPath = $sourcePathOrBytes;
                $hash = md5($docxPath . '_' . filemtime($docxPath) . '_' . filesize($docxPath));
            }

            // Cache directory
            $cacheDir = storage_path('app/private/articles/pdf');
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }
            $cachedPdf = $cacheDir . '/dompdf_' . self::RENDERER_VERSION . '_' . $hash . '.pdf';

            if (file_exists($cachedPdf) && filesize($cachedPdf) > 1000) {
                if ($tempDocx && file_exists($tempDocx)) {
                    @unlink($tempDocx);
                }
                return $cachedPdf;
            }

            $instance = new self();
            $pdfBytes = $instance->renderDocxToPdfBytes($docxPath);

            if ($tempDocx && file_exists($tempDocx)) {
                @unlink($tempDocx);
            }

            if ($pdfBytes && str_starts_with($pdfBytes, '%PDF-')) {
                @file_put_contents($cachedPdf, $pdfBytes);
                if (file_exists($cachedPdf) && filesize($cachedPdf) > 1000) {
                    return $cachedPdf;
                }
                // Fallback to temp dir if cacheDir is not writable
                $fallbackPath = sys_get_temp_dir() . '/dompdf_' . self::RENDERER_VERSION . '_' . $hash . '.pdf';
                @file_put_contents($fallbackPath, $pdfBytes);
                if (file_exists($fallbackPath) && filesize($fallbackPath) > 1000) {
                    return $fallbackPath;
                }
            }
        } catch (\Throwable $e) {
            Log::error('DocxToPdfService conversion failed: ' . $e->getMessage());
            if ($tempDocx && file_exists($tempDocx)) {
                @unlink($tempDocx);
            }
        }

        return null;
    }

    /**
     * Parse the DOCX archive and compile it into PDF bytes via DomPDF.
     */
    protected function renderDocxToPdfBytes(string $docxPath): ?string
    {
        $this->zip = new \ZipArchive();
        if ($this->zip->open($docxPath) !== true) {
            return null;
        }

        $docXml = $this->zip->getFromName('word/document.xml');
        if ($docXml === false || empty($docXml)) {
            $this->zip->close();
            return null;
        }

        // Detect whether extended Unicode characters (beyond WinAnsi) exist
        $plainPreview = strip_tags($docXml);
        $plainPreview = str_replace(['●', '○', '■', '▪', '▫'], '', $plainPreview);
        $this->useUnicodeFonts = $this->containsNonWinAnsi($plainPreview);

        // Parse styles, numbering, and main document relationships
        $this->parseStyles();
        $this->parseNumbering();
        $docRels = $this->parseRels('word/_rels/document.xml.rels');

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($docXml)) {
            $this->zip->close();
            return null;
        }

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::W_NS);
        $body = $xpath->query('//w:body')->item(0);
        if (!$body) {
            $this->zip->close();
            return null;
        }

        // Split body children into Word sections (preserving exact column counts & page metrics)
        $sections = $this->splitIntoSections($body);

        // Determine page geometry from the last/primary sectPr
        $primarySect = end($sections)['sectPr'] ?? [];
        $pageW = $primarySect['pageW'] ?? 595.28;
        $pageH = $primarySect['pageH'] ?? 841.89;
        $orient = $primarySect['orient'] ?? 'portrait';
        $mLeft = $primarySect['mLeft'] ?? 54.0;
        $mRight = $primarySect['mRight'] ?? 54.0;
        $mTop = $primarySect['mTop'] ?? 65.0;
        $mBottom = $primarySect['mBottom'] ?? 55.0;
        $mHeader = $primarySect['mHeader'] ?? 22.0;
        $mFooter = $primarySect['mFooter'] ?? 22.0;

        // Render running header & footer from the first available headerReference / footerReference
        $headerHtml = '';
        $footerHtml = '';
        $headerRefId = null;
        $footerRefId = null;

        foreach ($sections as $sec) {
            if (!$headerRefId && !empty($sec['sectPr']['headerRef'])) {
                $headerRefId = $sec['sectPr']['headerRef'];
            }
            if (!$footerRefId && !empty($sec['sectPr']['footerRef'])) {
                $footerRefId = $sec['sectPr']['footerRef'];
            }
        }

        $contentW = $pageW - $mLeft - $mRight;

        if ($headerRefId && isset($docRels[$headerRefId])) {
            $headerTarget = $this->normalizeWordTarget($docRels[$headerRefId]['target']);
            $headerHtml = $this->renderHeaderOrFooter($headerTarget, $contentW, true);
        }

        if ($footerRefId && isset($docRels[$footerRefId])) {
            $footerTarget = $this->normalizeWordTarget($docRels[$footerRefId]['target']);
            $footerHtml = $this->renderHeaderOrFooter($footerTarget, $contentW, false);
        }

        // Adjust page margins so fixed running header/footer never overlap body text
        $effectiveTop = $headerHtml !== '' ? max($mTop, 74.0) : $mTop;
        $effectiveBottom = $footerHtml !== '' ? max($mBottom, 48.0) : $mBottom;
        $usableH = $pageH - $effectiveTop - $effectiveBottom;

        // Render each section (supporting both 1-column and 2-column sections)
        $bodyHtmlParts = [];
        $usedHeightOnCurrentPage = 0.0;

        foreach ($sections as $secIdx => $section) {
            $cols = (int) ($section['sectPr']['cols'] ?? 1);
            $colSpace = (float) ($section['sectPr']['colSpace'] ?? 21.25);
            $breakType = $section['sectPr']['type'] ?? 'nextPage';

            if ($secIdx > 0 && $breakType !== 'continuous') {
                $bodyHtmlParts[] = '<div class="page-break"></div>';
                $usedHeightOnCurrentPage = 0.0;
            }

            if ($cols >= 2) {
                $twoColHtml = $this->renderTwoColumnSection(
                    $section['nodes'],
                    $docRels,
                    $contentW,
                    $colSpace,
                    $usableH,
                    $usedHeightOnCurrentPage
                );
                $bodyHtmlParts[] = $twoColHtml;
                $usedHeightOnCurrentPage = 0.0;
            } else {
                foreach ($section['nodes'] as $node) {
                    $rendered = $this->renderBlockNode($node, $docRels, $contentW);
                    if ($rendered['html'] !== '') {
                        $bodyHtmlParts[] = $rendered['html'];
                        $usedHeightOnCurrentPage += $rendered['estHeight'];
                        if ($usedHeightOnCurrentPage > $usableH) {
                            $usedHeightOnCurrentPage = fmod($usedHeightOnCurrentPage, $usableH);
                        }
                    }
                }
            }
        }

        $this->zip->close();

        // Build complete standalone HTML document for DomPDF
        $html = $this->buildFullHtml(
            implode("\n", $bodyHtmlParts),
            $headerHtml,
            $footerHtml,
            $pageW,
            $pageH,
            $effectiveTop,
            $mRight,
            $effectiveBottom,
            $mLeft,
            $mHeader,
            $mFooter
        );

        // Compile to PDF via DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', $this->useUnicodeFonts ? 'DejaVu Serif' : 'Times-Roman');
        $options->set('dpi', 96);
        $options->set('isFontSubsettingEnabled', true);

        $tempDir = storage_path('app/private/dompdf_temp');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }
        if (is_dir($tempDir) && is_writable($tempDir)) {
            $options->set('tempDir', $tempDir);
            $options->set('fontCache', $tempDir);
        }

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper([0, 0, $pageW, $pageH], $orient);
        $dompdf->render();

        // Stamp exact dynamic "Page X of Y" in the footer if footer XML contained PAGE/NUMPAGES fields
        if ($this->footerHasPageNumbers) {
            $canvas = $dompdf->getCanvas();
            $fontMetrics = $dompdf->getFontMetrics();
            $sansName = $this->useUnicodeFonts ? 'DejaVu Sans' : 'Helvetica';
            $regularFont = $fontMetrics->getFont($sansName, 'normal');
            $boldFont = $fontMetrics->getFont($sansName, 'bold');
            $rightEdge = $pageW - $mRight;
            $footerBaselineY = $pageH - max(18.0, $mFooter + 5.0);

            $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fm) use ($regularFont, $boldFont, $rightEdge, $footerBaselineY) {
                $size = 7.5;
                $part1 = 'Page ';
                $part2 = (string) $pageNumber;
                $part3 = ' of ' . (string) $pageCount;

                $w1 = $fm->getTextWidth($part1, $regularFont, $size);
                $w2 = $fm->getTextWidth($part2, $boldFont, $size);
                $w3 = $fm->getTextWidth($part3, $regularFont, $size);
                $startX = $rightEdge - ($w1 + $w2 + $w3);

                $canvas->text($startX, $footerBaselineY, $part1, $regularFont, $size, [0.333, 0.333, 0.333]);
                $canvas->text($startX + $w1, $footerBaselineY, $part2, $boldFont, $size, [0.004, 0.267, 0.545]);
                $canvas->text($startX + $w1 + $w2, $footerBaselineY, $part3, $regularFont, $size, [0.333, 0.333, 0.333]);
            });
        }

        return $dompdf->output();
    }

    /**
     * Build the full HTML wrapper with exact @page dimensions, fixed header/footer, and optional watermark.
     */
    protected function buildFullHtml(
        string $bodyHtml,
        string $headerHtml,
        string $footerHtml,
        float $pageW,
        float $pageH,
        float $mTop,
        float $mRight,
        float $mBottom,
        float $mLeft,
        float $mHeader,
        float $mFooter
    ): string {
        $baseFont = $this->useUnicodeFonts
            ? "'DejaVu Serif', serif"
            : "Times, 'Times New Roman', serif";

        $headerTopOffset = -($mTop - min($mHeader, 20.0));
        $footerBottomOffset = -($mBottom - min($mFooter, 18.0));

        $watermarkHtml = '';
        if ($this->watermarkText !== '') {
            $wmSafe = htmlspecialchars($this->watermarkText, ENT_QUOTES, 'UTF-8');
            $watermarkHtml = '<div class="doc-watermark">' . $wmSafe . '</div>';
        }

        $headerBlock = $headerHtml !== ''
            ? '<div class="doc-header">' . $headerHtml . '</div>'
            : '';

        $footerBlock = $footerHtml !== ''
            ? '<div class="doc-footer">' . $footerHtml . '</div>'
            : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    @page {
        size: {$pageW}pt {$pageH}pt;
        margin: {$mTop}pt {$mRight}pt {$mBottom}pt {$mLeft}pt;
    }
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        padding: 0;
        font-family: {$baseFont};
        font-size: 10pt;
        line-height: 1.22;
        color: #111111;
    }
    p {
        margin: 0;
        padding: 0;
    }
    table {
        border-collapse: collapse;
    }
    img {
        max-width: 100%;
        height: auto;
    }
    a {
        text-decoration: none;
    }
    .doc-header {
        position: fixed;
        top: {$headerTopOffset}pt;
        left: 0;
        right: 0;
    }
    .doc-footer {
        position: fixed;
        bottom: {$footerBottomOffset}pt;
        left: 0;
        right: 0;
    }
    .doc-watermark {
        position: fixed;
        top: 38%;
        left: -10%;
        width: 120%;
        text-align: center;
        font-family: Helvetica, 'DejaVu Sans', sans-serif;
        font-size: 56pt;
        font-weight: bold;
        color: #000000;
        opacity: 0.06;
        transform: rotate(-42deg);
        z-index: -1000;
    }
    .page-break {
        page-break-after: always;
        height: 0;
        margin: 0;
        padding: 0;
    }
    .two-col-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin: 0;
        padding: 0;
    }
    .two-col-table > tbody > tr > td {
        vertical-align: top;
        padding: 0;
        margin: 0;
    }
    .list-item {
        width: 100%;
        border-collapse: collapse;
    }
    .list-item td {
        vertical-align: top;
        padding: 0;
    }
</style>
</head>
<body>
{$watermarkHtml}
{$headerBlock}
{$footerBlock}
<main>
{$bodyHtml}
</main>
</body>
</html>
HTML;
    }

    /**
     * Split <w:body> children into ordered sections according to inline <w:pPr><w:sectPr>
     * and the final body <w:sectPr>.
     */
    protected function splitIntoSections(\DOMNode $body): array
    {
        $sections = [];
        $currentNodes = [];

        foreach ($body->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }
            $local = $child->localName;

            if ($local === 'sectPr') {
                $sections[] = [
                    'nodes' => $currentNodes,
                    'sectPr' => $this->parseSectPr($child),
                ];
                $currentNodes = [];
                continue;
            }

            if ($local === 'p') {
                $pSectPr = $this->findDirectChildSectPr($child);
                if ($pSectPr !== null) {
                    // Include paragraph content if it has visible text/images, then end section
                    if (trim($child->textContent) !== '' || $this->nodeHasDrawing($child)) {
                        $currentNodes[] = $child;
                    }
                    $sections[] = [
                        'nodes' => $currentNodes,
                        'sectPr' => $this->parseSectPr($pSectPr),
                    ];
                    $currentNodes = [];
                    continue;
                }
            }

            $currentNodes[] = $child;
        }

        if (!empty($currentNodes)) {
            $sections[] = [
                'nodes' => $currentNodes,
                'sectPr' => [],
            ];
        }

        return $sections;
    }

    protected function findDirectChildSectPr(\DOMElement $p): ?\DOMElement
    {
        foreach ($p->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'pPr') {
                foreach ($c->childNodes as $pp) {
                    if ($pp->nodeType === XML_ELEMENT_NODE && $pp->localName === 'sectPr') {
                        return $pp;
                    }
                }
            }
        }
        return null;
    }

    protected function nodeHasDrawing(\DOMNode $node): bool
    {
        foreach ($node->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE) {
                if ($c->localName === 'drawing' || $c->localName === 'pict') {
                    return true;
                }
                if ($this->nodeHasDrawing($c)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Parse <w:sectPr> attributes (page size, margins, columns, header/footer references, break type).
     */
    protected function parseSectPr(\DOMElement $sectPr): array
    {
        $info = [
            'pageW' => 595.28,
            'pageH' => 841.89,
            'orient' => 'portrait',
            'mTop' => 65.0,
            'mRight' => 54.0,
            'mBottom' => 55.0,
            'mLeft' => 54.0,
            'mHeader' => 22.0,
            'mFooter' => 22.0,
            'cols' => 1,
            'colSpace' => 21.25,
            'type' => 'nextPage',
            'headerRef' => null,
            'footerRef' => null,
        ];

        foreach ($sectPr->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            switch ($c->localName) {
                case 'pgSz':
                    $w = (int) $c->getAttributeNS(self::W_NS, 'w');
                    $h = (int) $c->getAttributeNS(self::W_NS, 'h');
                    $orient = $c->getAttributeNS(self::W_NS, 'orient');
                    if ($w > 0) $info['pageW'] = $w / 20.0;
                    if ($h > 0) $info['pageH'] = $h / 20.0;
                    if ($orient === 'landscape') $info['orient'] = 'landscape';
                    break;
                case 'pgMar':
                    foreach (['top' => 'mTop', 'right' => 'mRight', 'bottom' => 'mBottom', 'left' => 'mLeft', 'header' => 'mHeader', 'footer' => 'mFooter'] as $attr => $key) {
                        $val = $c->getAttributeNS(self::W_NS, $attr);
                        if ($val !== '') $info[$key] = ((int) $val) / 20.0;
                    }
                    break;
                case 'cols':
                    $num = (int) $c->getAttributeNS(self::W_NS, 'num');
                    $space = (int) $c->getAttributeNS(self::W_NS, 'space');
                    if ($num > 0) $info['cols'] = $num;
                    if ($space > 0) $info['colSpace'] = $space / 20.0;
                    break;
                case 'type':
                    $val = $c->getAttributeNS(self::W_NS, 'val');
                    if ($val !== '') $info['type'] = $val;
                    break;
                case 'headerReference':
                    $t = $c->getAttributeNS(self::W_NS, 'type') ?: 'default';
                    $rid = $c->getAttributeNS(self::R_NS, 'id');
                    if ($rid && ($t === 'default' || $info['headerRef'] === null)) {
                        $info['headerRef'] = $rid;
                    }
                    break;
                case 'footerReference':
                    $t = $c->getAttributeNS(self::W_NS, 'type') ?: 'default';
                    $rid = $c->getAttributeNS(self::R_NS, 'id');
                    if ($rid && ($t === 'default' || $info['footerRef'] === null)) {
                        $info['footerRef'] = $rid;
                    }
                    break;
            }
        }

        return $info;
    }

    /**
     * Render a 2-column Word section into page-bounded balanced 2-column HTML tables
     * so DomPDF renders authentic academic two-column pages without overflow or clipping.
     */
    protected function renderTwoColumnSection(
        array $nodes,
        array $rels,
        float $contentW,
        float $colSpace,
        float $usableH,
        float $usedHeightOnFirstPage
    ): string {
        $colW = ($contentW - $colSpace) / 2.0;
        $fullPageBudget = $usableH * 0.85;
        $firstPageBudget = max(130.0, ($usableH * 0.86) - $usedHeightOnFirstPage);

        // Pre-render all blocks in this section
        $blocks = [];
        foreach ($nodes as $node) {
            $item = $this->renderBlockNode($node, $rels, $colW, true);
            if ($item['html'] === '') {
                continue;
            }
            // If this block is a wide table or figure wider than a single column, re-render at full width
            if (!empty($item['fullWidth'])) {
                $item = $this->renderBlockNode($node, $rels, $contentW, false);
                $item['fullWidth'] = true;
            }
            $blocks[] = $item;
        }

        if (empty($blocks)) {
            return '';
        }

        $pagesOut = [];
        $budget = $firstPageBudget;
        $isFirstPage = true;
        $leftHtml = [];
        $rightHtml = [];
        $leftH = 0.0;
        $rightH = 0.0;
        $inRight = false;

        $flushTwoColRow = function (bool $addPageBreak = false) use (
            &$pagesOut, &$leftHtml, &$rightHtml, &$leftH, &$rightH, &$inRight, $colW, $colSpace
        ) {
            if (empty($leftHtml) && empty($rightHtml)) {
                return;
            }
            $lStr = implode("\n", $leftHtml);
            $rStr = implode("\n", $rightHtml);
            $pagesOut[] = sprintf(
                '<table class="two-col-table"><tbody><tr><td style="width:%.2Fpt;">%s</td><td style="width:%.2Fpt;"></td><td style="width:%.2Fpt;">%s</td></tr></tbody></table>',
                $colW,
                $lStr,
                $colSpace,
                $colW,
                $rStr
            );
            if ($addPageBreak) {
                $pagesOut[] = '<div class="page-break"></div>';
            }
            $leftHtml = [];
            $rightHtml = [];
            $leftH = 0.0;
            $rightH = 0.0;
            $inRight = false;
        };

        $i = 0;
        $total = count($blocks);
        while ($i < $total) {
            $blk = $blocks[$i];

            // Handle full-width spanning element (wide table/figure)
            if (!empty($blk['fullWidth'])) {
                $flushTwoColRow(false);
                $pagesOut[] = '<div style="width:100%; margin:4pt 0;">' . $blk['html'] . '</div>';
                $budget = max(80.0, $budget - $blk['estHeight'] - 10.0);
                if ($budget < 90.0 && $i + 1 < $total) {
                    $pagesOut[] = '<div class="page-break"></div>';
                    $budget = $fullPageBudget;
                    $isFirstPage = false;
                }
                $i++;
                continue;
            }

            // On the final portion that fits on 1 page (< 2 * budget total), balance left & right columns
            if (!$isFirstPage && !$inRight && empty($leftHtml)) {
                $remTotalH = 0.0;
                $hasFullWidthAhead = false;
                for ($k = $i; $k < $total; $k++) {
                    if (!empty($blocks[$k]['fullWidth'])) {
                        $hasFullWidthAhead = true;
                        break;
                    }
                    $remTotalH += $blocks[$k]['estHeight'];
                }
                if (!$hasFullWidthAhead && $remTotalH <= ($budget * 1.90)) {
                    $halfTarget = $remTotalH / 2.0;
                    for ($k = $i; $k < $total; $k++) {
                        if (!$inRight && $leftH >= $halfTarget && !empty($leftHtml) && empty($blocks[$k]['keepWithPrev'])) {
                            $inRight = true;
                        }
                        if (!$inRight) {
                            $leftHtml[] = $blocks[$k]['html'];
                            $leftH += $blocks[$k]['estHeight'];
                        } else {
                            $rightHtml[] = $blocks[$k]['html'];
                            $rightH += $blocks[$k]['estHeight'];
                        }
                    }
                    $flushTwoColRow(false);
                    break;
                }
            }

            $currentColH = $inRight ? $rightH : $leftH;
            $remainingInCol = $budget - $currentColH;

            // Avoid orphan heading at very bottom of a column
            if (!empty($blk['isHeading']) && $remainingInCol < 48.0 && $currentColH > 0.0) {
                if (!$inRight) {
                    $inRight = true;
                } else {
                    $flushTwoColRow(true);
                    $budget = $fullPageBudget;
                    $isFirstPage = false;
                }
                continue;
            }

            // Fits comfortably in current column
            if ($blk['estHeight'] <= $remainingInCol || ($currentColH === 0.0 && $blk['estHeight'] <= $fullPageBudget)) {
                if (!$inRight) {
                    $leftHtml[] = $blk['html'];
                    $leftH += $blk['estHeight'];
                } else {
                    $rightHtml[] = $blk['html'];
                    $rightH += $blk['estHeight'];
                }
                $i++;
                continue;
            }

            // Try splitting a long splittable text paragraph across column boundary (just like Word!)
            if (!empty($blk['splittable']) && $remainingInCol >= ($blk['lineHeight'] * 3.0)) {
                $split = $this->splitParagraphBlock($blk, $remainingInCol, $colW);
                if ($split !== null) {
                    if (!$inRight) {
                        $leftHtml[] = $split['first']['html'];
                        $leftH += $split['first']['estHeight'];
                        $inRight = true;
                    } else {
                        $rightHtml[] = $split['first']['html'];
                        $rightH += $split['first']['estHeight'];
                        $flushTwoColRow(true);
                        $budget = $fullPageBudget;
                        $isFirstPage = false;
                    }
                    $blocks[$i] = $split['rest'];
                    continue;
                }
            }

            // Move to right column or next page
            if (!$inRight) {
                if (empty($leftHtml)) {
                    // Single oversized element: place it so we never infinite-loop
                    $leftHtml[] = $blk['html'];
                    $leftH += $blk['estHeight'];
                    $i++;
                }
                $inRight = true;
            } else {
                if (empty($rightHtml)) {
                    $rightHtml[] = $blk['html'];
                    $rightH += $blk['estHeight'];
                    $i++;
                }
                $flushTwoColRow(true);
                $budget = $fullPageBudget;
                $isFirstPage = false;
            }
        }

        $flushTwoColRow(false);

        return implode("\n", $pagesOut);
    }

    /**
     * Split a plain/styled text paragraph block cleanly at a word boundary across columns.
     */
    protected function splitParagraphBlock(array $blk, float $availH, float $colW): ?array
    {
        $plain = $blk['plainText'] ?? '';
        if (mb_strlen($plain) < 120) {
            return null;
        }

        $lineH = max(10.0, (float) ($blk['lineHeight'] ?? 12.2));
        $fontSize = max(7.0, (float) ($blk['fontSize'] ?? 10.0));
        $spacingBefore = (float) ($blk['spacingBefore'] ?? 0.0);
        $maxLines = (int) floor(($availH - $spacingBefore) / $lineH);
        if ($maxLines < 3) {
            return null;
        }

        $charsPerLine = max(25, (int) floor($colW / ($fontSize * 0.51)));
        $targetChars = $maxLines * $charsPerLine;
        if ($targetChars >= mb_strlen($plain) - 40) {
            return null;
        }

        // Find nearest space before targetChars
        $cutPos = mb_strrpos(mb_substr($plain, 0, $targetChars), ' ');
        if ($cutPos === false || $cutPos < 60) {
            return null;
        }

        $part1Text = trim(mb_substr($plain, 0, $cutPos));
        $part2Text = trim(mb_substr($plain, $cutPos + 1));
        if ($part1Text === '' || $part2Text === '') {
            return null;
        }

        $pStyleBase = $blk['pStyleBase'] ?? '';
        $runStyleBase = $blk['runStyleBase'] ?? '';

        // First fragment: remove bottom margin so text sits cleanly at bottom of column
        $style1 = preg_replace('/margin-bottom:[^;]+;/', '', $pStyleBase) . 'margin-bottom:0pt;';
        // Continuation fragment: remove first-line indent and top margin
        $style2 = preg_replace('/(text-indent|margin-top):[^;]+;/', '', $pStyleBase) . 'text-indent:0pt;margin-top:0pt;';

        $html1 = '<p style="' . $style1 . '"><span style="' . $runStyleBase . '">' . htmlspecialchars($part1Text, ENT_QUOTES, 'UTF-8') . '</span></p>';
        $html2 = '<p style="' . $style2 . '"><span style="' . $runStyleBase . '">' . htmlspecialchars($part2Text, ENT_QUOTES, 'UTF-8') . '</span></p>';

        $lines1 = max(1, (int) ceil(mb_strlen($part1Text) / $charsPerLine));
        $lines2 = max(1, (int) ceil(mb_strlen($part2Text) / $charsPerLine));

        return [
            'first' => [
                'html' => $html1,
                'estHeight' => $spacingBefore + ($lines1 * $lineH),
            ],
            'rest' => array_merge($blk, [
                'html' => $html2,
                'plainText' => $part2Text,
                'spacingBefore' => 0.0,
                'estHeight' => ($lines2 * $lineH) + ($blk['spacingAfter'] ?? 4.0),
                'pStyleBase' => $style2,
            ]),
        ];
    }

    /**
     * Render a single top-level block node (<w:p> or <w:tbl>) and estimate its rendered height.
     */
    protected function renderBlockNode(\DOMNode $node, array $rels, float $containerW, bool $inTwoCol = false): array
    {
        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return ['html' => '', 'estHeight' => 0.0];
        }

        if ($node->localName === 'p') {
            return $this->renderParagraph($node, $rels, $containerW, $inTwoCol);
        }

        if ($node->localName === 'tbl') {
            return $this->renderTable($node, $rels, $containerW, $inTwoCol);
        }

        return ['html' => '', 'estHeight' => 0.0];
    }

    /**
     * Render a <w:p> paragraph with full style inheritance, borders, shading, lists, runs, hyperlinks, and images.
     */
    protected function renderParagraph(\DOMElement $p, array $rels, float $containerW, bool $inTwoCol = false, bool $isHeaderFooter = false): array
    {
        $pPrNode = null;
        foreach ($p->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'pPr') {
                $pPrNode = $c;
                break;
            }
        }

        // Resolve paragraph style inheritance
        $styleId = null;
        $directPPr = [];
        $directPRPr = [];
        if ($pPrNode) {
            foreach ($pPrNode->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE) continue;
                if ($c->localName === 'pStyle') {
                    $styleId = $c->getAttributeNS(self::W_NS, 'val');
                } elseif ($c->localName === 'rPr') {
                    $directPRPr = $this->parseRPr($c);
                }
            }
            $directPPr = $this->parsePPr($pPrNode);
        }

        $resolvedStyle = $this->resolveStyle($styleId);
        $pPr = array_merge($this->docDefaultPPr, $resolvedStyle['pPr'], $directPPr);
        $inheritedRPr = array_merge($this->docDefaultRPr, $resolvedStyle['rPr'], $directPRPr);

        // Extract list prefix if paragraph has <w:numPr>
        $listPrefix = null;
        $listIndentLeft = 0.0;
        if (!empty($pPr['numId'])) {
            $listInfo = $this->getListMarker($pPr['numId'], (int) ($pPr['ilvl'] ?? 0));
            if ($listInfo !== null) {
                $listPrefix = $listInfo['marker'];
                $listIndentLeft = $listInfo['indentLeft'];
            }
        }

        // Check for VML watermark in headers
        if ($isHeaderFooter) {
            $this->extractWatermarkFromNode($p);
        }

        // Special handling for tab-aligned footer paragraphs (e.g. Left \t Center \t Right)
        if ($isHeaderFooter && !empty($pPr['hasTabs'])) {
            $footerTable = $this->renderTabAlignedFooterParagraph($p, $pPr, $inheritedRPr, $rels, $containerW);
            if ($footerTable !== null) {
                return ['html' => $footerTable, 'estHeight' => 20.0];
            }
        }

        // Render child runs, hyperlinks, and math equations
        $innerHtml = '';
        $plainText = '';
        $hasComplexInline = false;
        $maxImageH = 0.0;
        $maxImageW = 0.0;
        $maxFontSize = (float) ($inheritedRPr['size'] ?? 10.0);
        $firstRunStyle = '';
        $runCount = 0;

        foreach ($p->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) continue;
            $local = $child->localName;

            if ($local === 'r') {
                $res = $this->renderRun($child, $inheritedRPr, $rels, $containerW, $isHeaderFooter);
                $innerHtml .= $res['html'];
                $plainText .= $res['plain'];
                if ($res['hasImage'] || $res['hasFormatting']) {
                    $hasComplexInline = true;
                }
                if ($res['imageH'] > $maxImageH) $maxImageH = $res['imageH'];
                if ($res['imageW'] > $maxImageW) $maxImageW = $res['imageW'];
                if ($res['fontSize'] > $maxFontSize) $maxFontSize = $res['fontSize'];
                if ($runCount === 0) $firstRunStyle = $res['runCss'];
                $runCount++;
            } elseif ($local === 'hyperlink') {
                $hasComplexInline = true;
                $hrefRid = $child->getAttributeNS(self::R_NS, 'id');
                $href = ($hrefRid && isset($rels[$hrefRid])) ? $rels[$hrefRid]['target'] : '';
                $linkInner = '';
                foreach ($child->childNodes as $hc) {
                    if ($hc->nodeType === XML_ELEMENT_NODE && $hc->localName === 'r') {
                        $res = $this->renderRun($hc, $inheritedRPr, $rels, $containerW, $isHeaderFooter);
                        $linkInner .= $res['html'];
                        $plainText .= $res['plain'];
                    }
                }
                if ($href !== '' && preg_match('/^https?:\/\//i', $href)) {
                    $innerHtml .= '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . $linkInner . '</a>';
                } else {
                    $innerHtml .= $linkInner;
                }
            } elseif ($local === 'oMath' || $local === 'oMathPara') {
                $hasComplexInline = true;
                $mathTxt = trim($child->textContent);
                if ($mathTxt !== '') {
                    $innerHtml .= '<span style="font-style:italic;">' . htmlspecialchars($mathTxt, ENT_QUOTES, 'UTF-8') . '</span>';
                    $plainText .= $mathTxt;
                }
            }
        }

        // Empty paragraph with bottom/top border (e.g. accent rule lines in header!)
        $hasBorder = !empty($pPr['borders']);
        $hasShading = !empty($pPr['shd']);
        if (trim($plainText) === '' && $maxImageH <= 0 && !$hasBorder && !$hasShading) {
            // Preserve intentional vertical spacer only minimally
            return ['html' => '', 'estHeight' => 0.0];
        }

        // Build paragraph CSS
        $css = [];
        $spacingBefore = (float) ($pPr['spaceBefore'] ?? 0.0);
        $spacingAfter = (float) ($pPr['spaceAfter'] ?? ($isHeaderFooter ? 0.0 : 3.0));
        $lineRatio = (float) ($pPr['lineRatio'] ?? 1.22);

        $css[] = 'margin-top:' . round($spacingBefore, 2) . 'pt';
        $css[] = 'margin-bottom:' . round($spacingAfter, 2) . 'pt';
        $css[] = 'line-height:' . round($lineRatio, 2);

        if (!empty($pPr['align'])) {
            $css[] = 'text-align:' . $pPr['align'];
        }
        if ($listPrefix === null) {
            if (!empty($pPr['indLeft'])) {
                $css[] = 'margin-left:' . round((float) $pPr['indLeft'], 2) . 'pt';
            }
            if (!empty($pPr['indRight'])) {
                $css[] = 'margin-right:' . round((float) $pPr['indRight'], 2) . 'pt';
            }
            if (!empty($pPr['firstLine'])) {
                $css[] = 'text-indent:' . round((float) $pPr['firstLine'], 2) . 'pt';
            }
        }

        if ($hasShading) {
            $css[] = 'background-color:#' . $pPr['shd'];
            $css[] = 'padding:3pt 6pt';
        }
        if ($hasBorder) {
            foreach ($pPr['borders'] as $side => $bRule) {
                $css[] = "border-{$side}:{$bRule}";
            }
            if (trim($plainText) === '' && $maxImageH <= 0) {
                $css[] = 'height:0pt;line-height:0pt;font-size:0pt';
            }
        }

        // Default font on paragraph itself (important for empty or inherited text)
        $pFontFamily = $this->mapFontFamily($inheritedRPr['font'] ?? null);
        $css[] = 'font-family:' . $pFontFamily;
        $css[] = 'font-size:' . round($maxFontSize, 2) . 'pt';
        if (!empty($inheritedRPr['color'])) {
            $css[] = 'color:#' . $inheritedRPr['color'];
        }

        $pStyleStr = implode(';', $css) . ';';

        // Build final HTML
        if ($listPrefix !== null) {
            $markerW = max(14.0, min(26.0, mb_strlen($listPrefix) * 5.2 + 5.0));
            $padLeft = max(0.0, $listIndentLeft - $markerW);
            $html = '<table class="list-item" style="margin-top:' . round($spacingBefore, 2) . 'pt;margin-bottom:' . round($spacingAfter, 2) . 'pt;margin-left:' . round($padLeft, 2) . 'pt;"><tbody><tr>'
                . '<td style="width:' . round($markerW, 2) . 'pt;font-family:' . $pFontFamily . ';font-size:' . round($maxFontSize, 2) . 'pt;line-height:' . round($lineRatio, 2) . ';">'
                . htmlspecialchars($listPrefix, ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td style="font-family:' . $pFontFamily . ';font-size:' . round($maxFontSize, 2) . 'pt;line-height:' . round($lineRatio, 2) . ';'
                . (!empty($pPr['align']) ? 'text-align:' . $pPr['align'] . ';' : '') . '">'
                . $innerHtml . '</td></tr></tbody></table>';
        } else {
            $html = '<p style="' . $pStyleStr . '">' . ($innerHtml !== '' ? $innerHtml : '&nbsp;') . '</p>';
        }

        // Estimate rendered height in points
        $lineH = $maxFontSize * max(1.15, $lineRatio);
        $charsPerLine = max(20, (int) floor(($containerW - ($pPr['indLeft'] ?? 0) - ($pPr['indRight'] ?? 0)) / ($maxFontSize * 0.50)));
        $textLines = trim($plainText) !== '' ? max(1, (int) ceil(mb_strlen($plainText) / $charsPerLine)) : 0;
        $estH = $spacingBefore + $spacingAfter + ($textLines * $lineH) + $maxImageH;
        if ($hasBorder && $textLines === 0 && $maxImageH <= 0) {
            $estH = max(3.0, $spacingBefore + $spacingAfter + 2.0);
        }

        $isHeading = !empty($pPr['keepNext'])
            || ($styleId && stripos($styleId, 'Heading') !== false)
            || ($styleId && stripos($styleId, 'Title') !== false);

        // Allow splitting plain body paragraphs cleanly across columns
        $splittable = $inTwoCol
            && !$isHeading
            && !$hasComplexInline
            && !$hasBorder
            && !$hasShading
            && $listPrefix === null
            && $maxImageH <= 0
            && mb_strlen($plainText) >= 140;

        return [
            'html' => $html,
            'estHeight' => $estH,
            'isHeading' => $isHeading,
            'fullWidth' => ($inTwoCol && $maxImageW > ($containerW * 1.15)),
            'splittable' => $splittable,
            'plainText' => $plainText,
            'lineHeight' => $lineH,
            'fontSize' => $maxFontSize,
            'spacingBefore' => $spacingBefore,
            'spacingAfter' => $spacingAfter,
            'pStyleBase' => $pStyleStr,
            'runStyleBase' => $firstRunStyle,
        ];
    }

    /**
     * Render a tab-aligned footer paragraph (Left \t Center \t Right) as a 3-column HTML table
     * and flag dynamic PAGE/NUMPAGES stamping.
     */
    protected function renderTabAlignedFooterParagraph(\DOMElement $p, array $pPr, array $inheritedRPr, array $rels, float $containerW): ?string
    {
        $segments = [[]];
        $segIdx = 0;
        $inField = false;
        $fieldInstr = '';

        foreach ($p->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) continue;
            if ($child->localName === 'hyperlink') {
                $hrefRid = $child->getAttributeNS(self::R_NS, 'id');
                $href = ($hrefRid && isset($rels[$hrefRid])) ? $rels[$hrefRid]['target'] : '';
                $linkHtml = '';
                foreach ($child->childNodes as $hc) {
                    if ($hc->nodeType === XML_ELEMENT_NODE && $hc->localName === 'r') {
                        $res = $this->renderRun($hc, $inheritedRPr, $rels, $containerW, true);
                        $linkHtml .= $res['html'];
                    }
                }
                $segments[$segIdx][] = ($href !== '' && preg_match('/^https?:\/\//i', $href))
                    ? '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '">' . $linkHtml . '</a>'
                    : $linkHtml;
                continue;
            }
            if ($child->localName !== 'r') continue;

            // Inspect run children for <w:tab/>, <w:t> containing \t, or PAGE/NUMPAGES field codes
            $rPr = $inheritedRPr;
            foreach ($child->childNodes as $rc) {
                if ($rc->nodeType === XML_ELEMENT_NODE && $rc->localName === 'rPr') {
                    $rPr = array_merge($inheritedRPr, $this->parseRPr($rc));
                }
            }
            $rCss = $this->buildRunCss($rPr);

            foreach ($child->childNodes as $rc) {
                if ($rc->nodeType !== XML_ELEMENT_NODE) continue;
                $ln = $rc->localName;
                if ($ln === 'fldChar') {
                    $ft = $rc->getAttributeNS(self::W_NS, 'fldCharType');
                    if ($ft === 'begin') {
                        $inField = true;
                        $fieldInstr = '';
                    } elseif ($ft === 'end') {
                        if (stripos($fieldInstr, 'PAGE') !== false || stripos($fieldInstr, 'NUMPAGES') !== false) {
                            $this->footerHasPageNumbers = true;
                        }
                        $inField = false;
                        $fieldInstr = '';
                    }
                } elseif ($ln === 'instrText') {
                    $fieldInstr .= $rc->textContent;
                    if (stripos($fieldInstr, 'PAGE') !== false || stripos($fieldInstr, 'NUMPAGES') !== false) {
                        $this->footerHasPageNumbers = true;
                    }
                } elseif ($ln === 'tab') {
                    $segIdx++;
                    $segments[$segIdx] = [];
                } elseif ($ln === 't' && !$inField) {
                    $txt = $rc->textContent;
                    if (str_contains($txt, "\t")) {
                        $parts = explode("\t", $txt);
                        foreach ($parts as $pi => $part) {
                            if ($pi > 0) {
                                $segIdx++;
                                $segments[$segIdx] = [];
                            }
                            if ($part !== '') {
                                $segments[$segIdx][] = '<span style="' . $rCss . '">' . htmlspecialchars($part, ENT_QUOTES, 'UTF-8') . '</span>';
                            }
                        }
                    } elseif ($txt !== '') {
                        $segments[$segIdx][] = '<span style="' . $rCss . '">' . htmlspecialchars($txt, ENT_QUOTES, 'UTF-8') . '</span>';
                    }
                }
            }
        }

        if (count($segments) < 2) {
            return null;
        }

        $borderCss = '';
        if (!empty($pPr['borders'])) {
            foreach ($pPr['borders'] as $side => $bRule) {
                $borderCss .= "border-{$side}:{$bRule};";
            }
        }

        $leftHtml = implode('', $segments[0] ?? []);
        $centerHtml = count($segments) >= 3 ? implode('', $segments[1] ?? []) : '';
        $rightHtml = count($segments) >= 3 ? implode('', $segments[2] ?? []) : implode('', $segments[1] ?? []);

        // If dynamic page numbers are stamped via DomPDF page_script, clear static "Page  of " placeholder
        if ($this->footerHasPageNumbers && preg_match('/Page\s*(of)?\s*$/i', trim(strip_tags($rightHtml)))) {
            $rightHtml = '&nbsp;';
        }

        return '<table style="width:100%;border-collapse:collapse;' . $borderCss . 'padding-top:3pt;"><tbody><tr>'
            . '<td style="text-align:left;vertical-align:middle;padding-top:3pt;white-space:nowrap;">' . $leftHtml . '</td>'
            . '<td style="text-align:center;vertical-align:middle;padding-top:3pt;">' . $centerHtml . '</td>'
            . '<td style="text-align:right;vertical-align:middle;padding-top:3pt;width:72pt;white-space:nowrap;">' . $rightHtml . '</td>'
            . '</tr></tbody></table>';
    }

    /**
     * Render a <w:r> run (text, line breaks, tabs, inline images, superscript/subscript, formatting).
     */
    protected function renderRun(\DOMElement $r, array $inheritedRPr, array $rels, float $containerW, bool $isHeaderFooter = false): array
    {
        $directRPr = [];
        foreach ($r->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'rPr') {
                $directRPr = $this->parseRPr($c);
                break;
            }
        }

        // Check character style inheritance if rStyle is specified
        $charStyleRPr = [];
        if (!empty($directRPr['rStyle'])) {
            $charStyleRPr = $this->resolveStyle($directRPr['rStyle'])['rPr'];
        }

        $rPr = array_merge($inheritedRPr, $charStyleRPr, $directRPr);
        $runCss = $this->buildRunCss($rPr);
        $fontSize = (float) ($rPr['size'] ?? 10.0);

        $html = '';
        $plain = '';
        $hasImage = false;
        $imageH = 0.0;
        $imageW = 0.0;
        $hasFormatting = !empty($directRPr['bold']) || !empty($directRPr['italic'])
            || !empty($directRPr['underline']) || !empty($directRPr['vertAlign'])
            || !empty($directRPr['shd']) || !empty($directRPr['color']);

        $skipFieldText = false;

        foreach ($r->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            $ln = $c->localName;

            if ($ln === 'fldChar') {
                $ft = $c->getAttributeNS(self::W_NS, 'fldCharType');
                if ($ft === 'begin') $skipFieldText = true;
                elseif ($ft === 'end') $skipFieldText = false;
            } elseif ($ln === 'instrText') {
                if ($isHeaderFooter && (stripos($c->textContent, 'PAGE') !== false || stripos($c->textContent, 'NUMPAGES') !== false)) {
                    $this->footerHasPageNumbers = true;
                }
            } elseif ($ln === 't' && !$skipFieldText) {
                $txt = $this->normalizeTextForFont($c->textContent);
                $plain .= $txt;
                $escaped = htmlspecialchars($txt, ENT_QUOTES, 'UTF-8');
                $wrapped = $this->wrapWithRunFormatting($escaped, $rPr, $runCss);
                $html .= $wrapped;
            } elseif ($ln === 'tab') {
                $html .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                $plain .= '    ';
            } elseif ($ln === 'br') {
                $brType = $c->getAttributeNS(self::W_NS, 'type');
                if ($brType === 'page') {
                    $html .= '<div class="page-break"></div>';
                } else {
                    $html .= '<br>';
                }
            } elseif ($ln === 'drawing' || $ln === 'pict') {
                $img = $this->renderDrawingOrPict($c, $rels, $containerW);
                if ($img !== null) {
                    $html .= $img['html'];
                    $hasImage = true;
                    $imageH = max($imageH, $img['height']);
                    $imageW = max($imageW, $img['width']);
                }
            }
        }

        return [
            'html' => $html,
            'plain' => $plain,
            'hasImage' => $hasImage,
            'imageH' => $imageH,
            'imageW' => $imageW,
            'hasFormatting' => $hasFormatting,
            'fontSize' => $fontSize,
            'runCss' => $runCss,
        ];
    }

    /**
     * Wrap escaped run text with inline span, superscript, subscript, and inline shading badges.
     */
    protected function wrapWithRunFormatting(string $escaped, array $rPr, string $runCss): string
    {
        if ($escaped === '') {
            return '';
        }

        $tagOpen = '<span style="' . $runCss . '">';
        $tagClose = '</span>';

        if (!empty($rPr['vertAlign'])) {
            if ($rPr['vertAlign'] === 'superscript') {
                return '<sup style="' . $runCss . 'font-size:70%;line-height:0;vertical-align:super;">' . $escaped . '</sup>';
            }
            if ($rPr['vertAlign'] === 'subscript') {
                return '<sub style="' . $runCss . 'font-size:70%;line-height:0;vertical-align:sub;">' . $escaped . '</sub>';
            }
        }

        return $tagOpen . $escaped . $tagClose;
    }

    protected function buildRunCss(array $rPr): string
    {
        $css = [];
        if (!empty($rPr['font'])) {
            $css[] = 'font-family:' . $this->mapFontFamily($rPr['font']);
        }
        if (!empty($rPr['size'])) {
            $css[] = 'font-size:' . round((float) $rPr['size'], 2) . 'pt';
        }
        if (!empty($rPr['color'])) {
            $css[] = 'color:#' . $rPr['color'];
        }
        if (!empty($rPr['bold'])) {
            $css[] = 'font-weight:bold';
        }
        if (!empty($rPr['italic'])) {
            $css[] = 'font-style:italic';
        }
        if (!empty($rPr['underline'])) {
            $css[] = 'text-decoration:underline';
        } elseif (!empty($rPr['strike'])) {
            $css[] = 'text-decoration:line-through';
        }
        if (!empty($rPr['caps'])) {
            $css[] = 'text-transform:uppercase';
        }
        if (!empty($rPr['shd'])) {
            $css[] = 'background-color:#' . $rPr['shd'];
            $css[] = 'padding:1.5pt 3pt';
        }
        return implode(';', $css) . ($css ? ';' : '');
    }

    /**
     * Render an inline/anchored <w:drawing> or <w:pict> image as a base64 <img> tag.
     */
    protected function renderDrawingOrPict(\DOMElement $node, array $rels, float $containerW): ?array
    {
        $xpath = new \DOMXPath($node->ownerDocument);
        $xpath->registerNamespace('a', self::A_NS);
        $xpath->registerNamespace('r', self::R_NS);
        $xpath->registerNamespace('wp', self::WP_NS);
        $xpath->registerNamespace('v', self::V_NS);

        $rId = null;
        $blip = $xpath->query('.//a:blip', $node)->item(0);
        if ($blip instanceof \DOMElement) {
            $rId = $blip->getAttributeNS(self::R_NS, 'embed') ?: $blip->getAttributeNS(self::R_NS, 'link');
        }

        if (!$rId) {
            $imagedata = $xpath->query('.//v:imagedata', $node)->item(0);
            if ($imagedata instanceof \DOMElement) {
                $rId = $imagedata->getAttributeNS(self::R_NS, 'id');
            }
        }

        if (!$rId || !isset($rels[$rId])) {
            return null;
        }

        $target = $this->normalizeWordTarget($rels[$rId]['target']);
        $dataUri = $this->loadMediaDataUri($target);
        if (!$dataUri) {
            return null;
        }

        // Read exact dimensions from <wp:extent cx="..." cy="..."/> (1 pt = 12700 EMU)
        $wPt = 0.0;
        $hPt = 0.0;
        $extent = $xpath->query('.//wp:extent', $node)->item(0);
        if ($extent instanceof \DOMElement) {
            $cx = (float) $extent->getAttribute('cx');
            $cy = (float) $extent->getAttribute('cy');
            if ($cx > 0) $wPt = $cx / 12700.0;
            if ($cy > 0) $hPt = $cy / 12700.0;
        }

        if ($wPt <= 0) {
            $wPt = min($containerW, 200.0);
        }
        $naturalW = $wPt;

        if ($wPt > $containerW) {
            $scale = $containerW / $wPt;
            $wPt = $containerW;
            if ($hPt > 0) $hPt *= $scale;
        }

        $style = 'width:' . round($wPt, 2) . 'pt;';
        if ($hPt > 0) {
            $style .= 'height:' . round($hPt, 2) . 'pt;';
        } else {
            $style .= 'height:auto;';
            $hPt = $wPt * 0.65;
        }

        return [
            'html' => '<img src="' . $dataUri . '" style="' . $style . 'vertical-align:middle;" alt="">',
            'width' => $naturalW,
            'height' => $hPt,
        ];
    }

    /**
     * Render a <w:tbl> table with exact borders, shading, cell margins, widths, colspans, and rowspans.
     */
    protected function renderTable(\DOMElement $tbl, array $rels, float $containerW, bool $inTwoCol = false, bool $isHeaderFooter = false): array
    {
        $tblBorders = [];
        $tblCellMar = ['top' => 3.0, 'right' => 5.0, 'bottom' => 3.0, 'left' => 5.0];
        $tblW = 0.0;
        $tblAlign = '';
        $gridCols = [];
        $rows = [];

        foreach ($tbl->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            if ($c->localName === 'tblPr') {
                foreach ($c->childNodes as $tp) {
                    if ($tp->nodeType !== XML_ELEMENT_NODE) continue;
                    if ($tp->localName === 'tblW') {
                        $tw = (int) $tp->getAttributeNS(self::W_NS, 'w');
                        $tt = $tp->getAttributeNS(self::W_NS, 'type');
                        if ($tt === 'dxa' && $tw > 0) $tblW = $tw / 20.0;
                    } elseif ($tp->localName === 'tblBorders') {
                        $tblBorders = $this->parseBorders($tp);
                    } elseif ($tp->localName === 'tblCellMar') {
                        $tblCellMar = array_merge($tblCellMar, $this->parseMargins($tp));
                    } elseif ($tp->localName === 'jc') {
                        $tblAlign = $tp->getAttributeNS(self::W_NS, 'val');
                    }
                }
            } elseif ($c->localName === 'tblGrid') {
                foreach ($c->childNodes as $gc) {
                    if ($gc->nodeType === XML_ELEMENT_NODE && $gc->localName === 'gridCol') {
                        $gw = (int) $gc->getAttributeNS(self::W_NS, 'w');
                        $gridCols[] = $gw > 0 ? ($gw / 20.0) : 0.0;
                    }
                }
            } elseif ($c->localName === 'tr') {
                $rows[] = $c;
            }
        }

        if (empty($rows)) {
            return ['html' => '', 'estHeight' => 0.0];
        }

        $totalGridW = array_sum($gridCols);
        $declaredW = max($tblW, $totalGridW);

        // If inside a 2-column section and table is substantially wider than 1 column, mark fullWidth
        if ($inTwoCol && $declaredW > ($containerW * 1.15)) {
            return ['html' => 'PLACEHOLDER', 'estHeight' => 100.0, 'fullWidth' => true];
        }

        // Pre-parse cells to handle vMerge rowspans cleanly
        $grid = [];
        foreach ($rows as $rIdx => $tr) {
            $rowCells = [];
            $colCursor = 0;
            foreach ($tr->childNodes as $tc) {
                if ($tc->nodeType !== XML_ELEMENT_NODE || $tc->localName !== 'tc') continue;

                $tcPr = $this->parseTcPr($tc);
                $colspan = max(1, (int) ($tcPr['gridSpan'] ?? 1));
                $vMerge = $tcPr['vMerge'] ?? null; // 'restart' | 'continue' | null

                if ($vMerge === 'continue') {
                    // Increment rowspan of the cell above in the same column
                    for ($prevR = $rIdx - 1; $prevR >= 0; $prevR--) {
                        if (isset($grid[$prevR][$colCursor]) && !$grid[$prevR][$colCursor]['skip']) {
                            $grid[$prevR][$colCursor]['rowspan']++;
                            break;
                        }
                    }
                    $rowCells[$colCursor] = ['skip' => true];
                } else {
                    $rowCells[$colCursor] = [
                        'skip' => false,
                        'node' => $tc,
                        'tcPr' => $tcPr,
                        'colspan' => $colspan,
                        'rowspan' => 1,
                    ];
                }
                $colCursor += $colspan;
            }
            $grid[$rIdx] = $rowCells;
        }

        $numRows = count($grid);
        $totalEstH = 0.0;
        $rowsHtml = [];

        foreach ($grid as $rIdx => $rowCells) {
            $tdsHtml = [];
            $maxCellH = 14.0;
            $cellKeys = array_keys($rowCells);
            $lastColKey = !empty($cellKeys) ? end($cellKeys) : 0;

            foreach ($rowCells as $cIdx => $cell) {
                if (!empty($cell['skip'])) {
                    continue;
                }

                $tcPr = $cell['tcPr'];
                $colspan = $cell['colspan'];
                $rowspan = $cell['rowspan'];

                // Compute cell width percentage relative to table width
                $cellW = (float) ($tcPr['width'] ?? 0.0);
                if ($cellW <= 0 && !empty($gridCols)) {
                    for ($k = $cIdx; $k < $cIdx + $colspan; $k++) {
                        $cellW += $gridCols[$k] ?? 0.0;
                    }
                }
                $effectiveTableW = $declaredW > 0 ? min($declaredW, $containerW) : $containerW;
                $widthPct = ($declaredW > 0 && $cellW > 0) ? min(100.0, ($cellW / $declaredW) * 100.0) : 0.0;
                $cellRenderW = $widthPct > 0 ? ($effectiveTableW * ($widthPct / 100.0)) : ($effectiveTableW / max(1, count($rowCells)));

                // Merge table-level borders with cell-level border overrides
                $cellBorders = [
                    'top' => $rIdx === 0 ? ($tblBorders['top'] ?? 'none') : ($tblBorders['insideH'] ?? 'none'),
                    'bottom' => ($rIdx + $rowspan >= $numRows) ? ($tblBorders['bottom'] ?? 'none') : ($tblBorders['insideH'] ?? 'none'),
                    'left' => $cIdx === 0 ? ($tblBorders['left'] ?? 'none') : ($tblBorders['insideV'] ?? 'none'),
                    'right' => ($cIdx === $lastColKey) ? ($tblBorders['right'] ?? 'none') : ($tblBorders['insideV'] ?? 'none'),
                ];
                if (!empty($tcPr['borders'])) {
                    foreach ($tcPr['borders'] as $side => $rule) {
                        $cellBorders[$side] = $rule;
                    }
                }

                $mar = array_merge($tblCellMar, $tcPr['margins'] ?? []);
                $tdCss = [];
                if ($widthPct > 0) {
                    $tdCss[] = 'width:' . round($widthPct, 2) . '%';
                }
                foreach ($cellBorders as $side => $rule) {
                    $tdCss[] = "border-{$side}:{$rule}";
                }
                if (!empty($tcPr['shd'])) {
                    $tdCss[] = 'background-color:#' . $tcPr['shd'];
                }
                $tdCss[] = sprintf('padding:%.1Fpt %.1Fpt %.1Fpt %.1Fpt', $mar['top'], $mar['right'], $mar['bottom'], $mar['left']);
                $tdCss[] = 'vertical-align:' . ($tcPr['vAlign'] ?? 'top');

                // Render paragraphs / nested tables inside the cell
                $cellInner = [];
                $cellEstH = $mar['top'] + $mar['bottom'];
                $innerW = max(24.0, $cellRenderW - $mar['left'] - $mar['right']);

                foreach ($cell['node']->childNodes as $cc) {
                    if ($cc->nodeType !== XML_ELEMENT_NODE) continue;
                    if ($cc->localName === 'p') {
                        $pRes = $this->renderParagraph($cc, $rels, $innerW, false, $isHeaderFooter);
                        if ($pRes['html'] !== '') {
                            $cellInner[] = $pRes['html'];
                            $cellEstH += $pRes['estHeight'];
                        }
                    } elseif ($cc->localName === 'tbl') {
                        $tRes = $this->renderTable($cc, $rels, $innerW, false, $isHeaderFooter);
                        if ($tRes['html'] !== '') {
                            $cellInner[] = $tRes['html'];
                            $cellEstH += $tRes['estHeight'];
                        }
                    }
                }

                if ($cellEstH > $maxCellH) {
                    $maxCellH = $cellEstH;
                }

                $attrs = '';
                if ($colspan > 1) $attrs .= ' colspan="' . $colspan . '"';
                if ($rowspan > 1) $attrs .= ' rowspan="' . $rowspan . '"';

                $tdsHtml[] = '<td' . $attrs . ' style="' . implode(';', $tdCss) . ';">'
                    . (!empty($cellInner) ? implode("\n", $cellInner) : '&nbsp;')
                    . '</td>';
            }

            $rowsHtml[] = '<tr>' . implode('', $tdsHtml) . '</tr>';
            $totalEstH += $maxCellH;
        }

        $tblMargin = $isHeaderFooter ? '0' : ($tblAlign === 'center' ? '4pt auto' : '4pt 0');
        $tblWidthCss = 'width:100%;';
        if (!$isHeaderFooter && $declaredW > 0 && $declaredW < ($containerW * 0.92)) {
            $tblWidthCss = 'width:' . round($declaredW, 2) . 'pt;max-width:100%;';
        }

        $html = '<table style="' . $tblWidthCss . 'border-collapse:collapse;margin:' . $tblMargin . ';"><tbody>'
            . implode("\n", $rowsHtml)
            . '</tbody></table>';

        return [
            'html' => $html,
            'estHeight' => $totalEstH + ($isHeaderFooter ? 0.0 : 8.0),
            'fullWidth' => false,
        ];
    }

    /**
     * Render a header or footer XML part (word/header1.xml, word/footer1.xml) using its own .rels file.
     */
    protected function renderHeaderOrFooter(string $targetPath, float $contentW, bool $isHeader): string
    {
        $xml = $this->zip->getFromName($targetPath);
        if ($xml === false || empty($xml)) {
            return '';
        }

        $baseName = basename($targetPath);
        $relsPath = 'word/_rels/' . $baseName . '.rels';
        $partRels = $this->parseRels($relsPath);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) {
            return '';
        }

        $root = $dom->documentElement;
        if (!$root) {
            return '';
        }

        if ($isHeader) {
            $this->extractWatermarkFromNode($root);
        }

        $parts = [];
        foreach ($root->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) continue;
            if ($child->localName === 'tbl') {
                $res = $this->renderTable($child, $partRels, $contentW, false, true);
                if ($res['html'] !== '') $parts[] = $res['html'];
            } elseif ($child->localName === 'p') {
                $res = $this->renderParagraph($child, $partRels, $contentW, false, true);
                if ($res['html'] !== '') $parts[] = $res['html'];
            }
        }

        return implode("\n", $parts);
    }

    /**
     * Detect VML text watermark inside header XML (e.g. <v:textpath string="DRAFT" />).
     */
    protected function extractWatermarkFromNode(\DOMNode $node): void
    {
        if ($this->watermarkText !== '') {
            return;
        }
        $xpath = new \DOMXPath($node->ownerDocument);
        $xpath->registerNamespace('v', self::V_NS);
        $tp = $xpath->query('.//v:textpath', $node)->item(0);
        if ($tp instanceof \DOMElement) {
            $str = trim($tp->getAttribute('string'));
            if ($str !== '') {
                $this->watermarkText = $str;
            }
        }
    }

    /**
     * Parse word/styles.xml into a lookup table with docDefaults and style inheritance.
     */
    protected function parseStyles(): void
    {
        $xml = $this->zip->getFromName('word/styles.xml');
        if ($xml === false || empty($xml)) {
            return;
        }

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) {
            return;
        }

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::W_NS);

        $defRPr = $xpath->query('//w:docDefaults/w:rPrDefault/w:rPr')->item(0);
        if ($defRPr instanceof \DOMElement) {
            $this->docDefaultRPr = $this->parseRPr($defRPr);
        }

        $defPPr = $xpath->query('//w:docDefaults/w:pPrDefault/w:pPr')->item(0);
        if ($defPPr instanceof \DOMElement) {
            $this->docDefaultPPr = $this->parsePPr($defPPr);
        }

        foreach ($xpath->query('//w:style') as $styleNode) {
            if (!($styleNode instanceof \DOMElement)) continue;
            $id = $styleNode->getAttributeNS(self::W_NS, 'styleId');
            if ($id === '') continue;

            $basedOn = null;
            $pPr = [];
            $rPr = [];

            foreach ($styleNode->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE) continue;
                if ($c->localName === 'basedOn') {
                    $basedOn = $c->getAttributeNS(self::W_NS, 'val');
                } elseif ($c->localName === 'pPr') {
                    $pPr = $this->parsePPr($c);
                } elseif ($c->localName === 'rPr') {
                    $rPr = $this->parseRPr($c);
                }
            }

            $this->styles[$id] = [
                'basedOn' => $basedOn,
                'pPr' => $pPr,
                'rPr' => $rPr,
            ];
        }
    }

    /**
     * Resolve a styleId recursively through basedOn parents.
     */
    protected function resolveStyle(?string $styleId, int $depth = 0): array
    {
        if (empty($styleId) || !isset($this->styles[$styleId]) || $depth > 8) {
            return ['pPr' => [], 'rPr' => []];
        }

        $style = $this->styles[$styleId];
        $parent = !empty($style['basedOn']) ? $this->resolveStyle($style['basedOn'], $depth + 1) : ['pPr' => [], 'rPr' => []];

        return [
            'pPr' => array_merge($parent['pPr'], $style['pPr']),
            'rPr' => array_merge($parent['rPr'], $style['rPr']),
        ];
    }

    /**
     * Parse word/numbering.xml for bullet and numbered list definitions.
     */
    protected function parseNumbering(): void
    {
        $xml = $this->zip->getFromName('word/numbering.xml');
        if ($xml === false || empty($xml)) {
            return;
        }

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) {
            return;
        }

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::W_NS);

        $abstracts = [];
        foreach ($xpath->query('//w:abstractNum') as $abs) {
            if (!($abs instanceof \DOMElement)) continue;
            $absId = $abs->getAttributeNS(self::W_NS, 'abstractNumId');
            $levels = [];
            foreach ($abs->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE || $c->localName !== 'lvl') continue;
                $ilvl = (int) $c->getAttributeNS(self::W_NS, 'ilvl');
                $fmt = 'bullet';
                $txt = '•';
                $start = 1;
                $indLeft = 18.0;
                foreach ($c->childNodes as $lc) {
                    if ($lc->nodeType !== XML_ELEMENT_NODE) continue;
                    if ($lc->localName === 'numFmt') $fmt = $lc->getAttributeNS(self::W_NS, 'val');
                    elseif ($lc->localName === 'lvlText') $txt = $lc->getAttributeNS(self::W_NS, 'val');
                    elseif ($lc->localName === 'start') $start = (int) $lc->getAttributeNS(self::W_NS, 'val');
                    elseif ($lc->localName === 'pPr') {
                        $lp = $this->parsePPr($lc);
                        if (!empty($lp['indLeft'])) $indLeft = (float) $lp['indLeft'];
                    }
                }
                $levels[$ilvl] = ['fmt' => $fmt, 'text' => $txt, 'start' => $start, 'indentLeft' => $indLeft];
            }
            $abstracts[$absId] = $levels;
        }

        foreach ($xpath->query('//w:num') as $num) {
            if (!($num instanceof \DOMElement)) continue;
            $numId = $num->getAttributeNS(self::W_NS, 'numId');
            foreach ($num->childNodes as $c) {
                if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'abstractNumId') {
                    $absId = $c->getAttributeNS(self::W_NS, 'val');
                    if (isset($abstracts[$absId])) {
                        $this->numbering[$numId] = $abstracts[$absId];
                    }
                }
            }
        }
    }

    /**
     * Compute next formatted list marker for a (numId, ilvl) pair.
     */
    protected function getListMarker(string $numId, int $ilvl): ?array
    {
        if (!isset($this->numbering[$numId])) {
            return null;
        }
        $lvl = $this->numbering[$numId][$ilvl] ?? ($this->numbering[$numId][0] ?? null);
        if (!$lvl) {
            return null;
        }

        $key = $numId . ':' . $ilvl;
        if (!isset($this->listCounters[$key])) {
            $this->listCounters[$key] = max(1, (int) $lvl['start']);
        } else {
            $this->listCounters[$key]++;
        }
        $n = $this->listCounters[$key];

        if ($lvl['fmt'] === 'bullet') {
            $marker = $this->normalizeTextForFont($lvl['text'] ?: '•');
        } else {
            $numStr = match ($lvl['fmt']) {
                'lowerLetter' => chr(96 + (($n - 1) % 26) + 1),
                'upperLetter' => chr(64 + (($n - 1) % 26) + 1),
                'lowerRoman' => strtolower($this->toRoman($n)),
                'upperRoman' => $this->toRoman($n),
                default => (string) $n,
            };
            $marker = str_replace('%1', $numStr, $lvl['text'] ?: '%1.');
            $marker = preg_replace('/%\d+/', $numStr, $marker);
        }

        return [
            'marker' => $marker,
            'indentLeft' => (float) $lvl['indentLeft'],
        ];
    }

    protected function toRoman(int $num): string
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $res = '';
        foreach ($map as $sym => $val) {
            while ($num >= $val) {
                $res .= $sym;
                $num -= $val;
            }
        }
        return $res ?: 'I';
    }

    protected function parsePPr(\DOMElement $pPr): array
    {
        $out = [];
        foreach ($pPr->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            switch ($c->localName) {
                case 'jc':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['align'] = match ($v) {
                        'both', 'distribute' => 'justify',
                        'center' => 'center',
                        'right', 'end' => 'right',
                        default => 'left',
                    };
                    break;
                case 'spacing':
                    $before = $c->getAttributeNS(self::W_NS, 'before');
                    $after = $c->getAttributeNS(self::W_NS, 'after');
                    $line = $c->getAttributeNS(self::W_NS, 'line');
                    if ($before !== '') $out['spaceBefore'] = ((int) $before) / 20.0;
                    if ($after !== '') $out['spaceAfter'] = ((int) $after) / 20.0;
                    if ($line !== '' && (int) $line > 0) {
                        $ratio = ((int) $line) / 240.0;
                        if ($ratio >= 0.8 && $ratio <= 3.0) {
                            $out['lineRatio'] = max(1.12, $ratio * 1.12);
                        }
                    }
                    break;
                case 'ind':
                    $left = $c->getAttributeNS(self::W_NS, 'left');
                    $right = $c->getAttributeNS(self::W_NS, 'right');
                    $first = $c->getAttributeNS(self::W_NS, 'firstLine');
                    if ($left !== '') $out['indLeft'] = ((int) $left) / 20.0;
                    if ($right !== '') $out['indRight'] = ((int) $right) / 20.0;
                    if ($first !== '') $out['firstLine'] = ((int) $first) / 20.0;
                    break;
                case 'pBdr':
                    $out['borders'] = $this->parseBorders($c);
                    break;
                case 'shd':
                    $fill = $c->getAttributeNS(self::W_NS, 'fill');
                    if ($fill !== '' && strtolower($fill) !== 'auto' && strtoupper($fill) !== 'FFFFFF') {
                        $out['shd'] = $fill;
                    }
                    break;
                case 'keepNext':
                    $out['keepNext'] = true;
                    break;
                case 'tabs':
                    $out['hasTabs'] = true;
                    break;
                case 'numPr':
                    foreach ($c->childNodes as $nc) {
                        if ($nc->nodeType !== XML_ELEMENT_NODE) continue;
                        if ($nc->localName === 'numId') $out['numId'] = $nc->getAttributeNS(self::W_NS, 'val');
                        elseif ($nc->localName === 'ilvl') $out['ilvl'] = (int) $nc->getAttributeNS(self::W_NS, 'val');
                    }
                    break;
            }
        }
        return $out;
    }

    protected function parseRPr(\DOMElement $rPr): array
    {
        $out = [];
        foreach ($rPr->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            switch ($c->localName) {
                case 'rStyle':
                    $out['rStyle'] = $c->getAttributeNS(self::W_NS, 'val');
                    break;
                case 'rFonts':
                    $f = $c->getAttributeNS(self::W_NS, 'ascii') ?: $c->getAttributeNS(self::W_NS, 'hAnsi');
                    if ($f !== '') $out['font'] = $f;
                    break;
                case 'sz':
                    $sz = (int) $c->getAttributeNS(self::W_NS, 'val');
                    if ($sz > 0) $out['size'] = $sz / 2.0;
                    break;
                case 'color':
                    $col = $c->getAttributeNS(self::W_NS, 'val');
                    if ($col !== '' && strtolower($col) !== 'auto') $out['color'] = $col;
                    break;
                case 'b':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['bold'] = ($v !== '0' && strtolower($v) !== 'false');
                    break;
                case 'i':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['italic'] = ($v !== '0' && strtolower($v) !== 'false');
                    break;
                case 'u':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['underline'] = ($v !== 'none' && $v !== '0');
                    break;
                case 'strike':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['strike'] = ($v !== '0' && strtolower($v) !== 'false');
                    break;
                case 'caps':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['caps'] = ($v !== '0' && strtolower($v) !== 'false');
                    break;
                case 'vertAlign':
                    $out['vertAlign'] = $c->getAttributeNS(self::W_NS, 'val');
                    break;
                case 'shd':
                    $fill = $c->getAttributeNS(self::W_NS, 'fill');
                    if ($fill !== '' && strtolower($fill) !== 'auto' && strtoupper($fill) !== 'FFFFFF') {
                        $out['shd'] = $fill;
                    }
                    break;
            }
        }
        return $out;
    }

    protected function parseTcPr(\DOMElement $tc): array
    {
        $out = [];
        foreach ($tc->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE || $c->localName !== 'tcPr') continue;
            foreach ($c->childNodes as $tp) {
                if ($tp->nodeType !== XML_ELEMENT_NODE) continue;
                switch ($tp->localName) {
                    case 'tcW':
                        $w = (int) $tp->getAttributeNS(self::W_NS, 'w');
                        $t = $tp->getAttributeNS(self::W_NS, 'type');
                        if ($t === 'dxa' && $w > 0) $out['width'] = $w / 20.0;
                        break;
                    case 'gridSpan':
                        $out['gridSpan'] = (int) $tp->getAttributeNS(self::W_NS, 'val');
                        break;
                    case 'vMerge':
                        $v = $tp->getAttributeNS(self::W_NS, 'val');
                        $out['vMerge'] = ($v === 'restart') ? 'restart' : 'continue';
                        break;
                    case 'tcBorders':
                        $out['borders'] = $this->parseBorders($tp);
                        break;
                    case 'shd':
                        $fill = $tp->getAttributeNS(self::W_NS, 'fill');
                        if ($fill !== '' && strtolower($fill) !== 'auto') {
                            $out['shd'] = $fill;
                        }
                        break;
                    case 'tcMar':
                        $out['margins'] = $this->parseMargins($tp);
                        break;
                    case 'vAlign':
                        $v = $tp->getAttributeNS(self::W_NS, 'val');
                        $out['vAlign'] = match ($v) {
                            'center' => 'middle',
                            'bottom' => 'bottom',
                            default => 'top',
                        };
                        break;
                }
            }
        }
        return $out;
    }

    protected function parseBorders(\DOMElement $bdrNode): array
    {
        $out = [];
        foreach ($bdrNode->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            $side = $c->localName;
            if (!in_array($side, ['top', 'bottom', 'left', 'right', 'insideH', 'insideV'], true)) continue;

            $val = $c->getAttributeNS(self::W_NS, 'val');
            if ($val === '' || $val === 'none' || $val === 'nil') {
                $out[$side] = 'none';
                continue;
            }

            $sz = (int) $c->getAttributeNS(self::W_NS, 'sz'); // eighths of a point
            $pt = max(0.5, $sz / 8.0);
            $col = $c->getAttributeNS(self::W_NS, 'color');
            if ($col === '' || strtolower($col) === 'auto') $col = '888888';

            $style = match ($val) {
                'dashed', 'dashSmallGap' => 'dashed',
                'dotted' => 'dotted',
                'double' => 'double',
                default => 'solid',
            };
            $out[$side] = round($pt, 2) . 'pt ' . $style . ' #' . $col;
        }
        return $out;
    }

    protected function parseMargins(\DOMElement $marNode): array
    {
        $out = [];
        foreach ($marNode->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            $side = $c->localName;
            if (in_array($side, ['top', 'bottom', 'left', 'right'], true)) {
                $w = $c->getAttributeNS(self::W_NS, 'w');
                if ($w !== '') $out[$side] = ((int) $w) / 20.0;
            }
        }
        return $out;
    }

    protected function parseRels(string $relsPath): array
    {
        $xml = $this->zip->getFromName($relsPath);
        if ($xml === false || empty($xml)) {
            return [];
        }

        $dom = new \DOMDocument();
        if (!@$dom->loadXML($xml)) {
            return [];
        }

        $rels = [];
        foreach ($dom->getElementsByTagName('Relationship') as $rel) {
            $id = $rel->getAttribute('Id');
            $target = $rel->getAttribute('Target');
            $type = $rel->getAttribute('Type');
            if ($id !== '' && $target !== '') {
                $rels[$id] = ['target' => $target, 'type' => $type];
            }
        }
        return $rels;
    }

    protected function normalizeWordTarget(string $target): string
    {
        $target = ltrim($target, '/');
        if (str_starts_with($target, '../')) {
            return substr($target, 3);
        }
        if (!str_starts_with($target, 'word/')) {
            return 'word/' . $target;
        }
        return $target;
    }

    protected function loadMediaDataUri(string $zipPath): ?string
    {
        if (isset($this->mediaCache[$zipPath])) {
            return $this->mediaCache[$zipPath];
        }

        $bytes = $this->zip->getFromName($zipPath);
        if ($bytes === false || empty($bytes)) {
            $this->mediaCache[$zipPath] = null;
            return null;
        }

        $ext = strtolower(pathinfo($zipPath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            default => null,
        };

        if (!$mime) {
            // Skip EMF/WMF vector images unsupported by DomPDF
            $this->mediaCache[$zipPath] = null;
            return null;
        }

        $uri = 'data:' . $mime . ';base64,' . base64_encode($bytes);
        $this->mediaCache[$zipPath] = $uri;
        return $uri;
    }

    protected function mapFontFamily(?string $font): string
    {
        $f = strtolower(trim((string) $font));

        $isSans = str_contains($f, 'arial')
            || str_contains($f, 'calibri')
            || str_contains($f, 'helvetica')
            || str_contains($f, 'segoe')
            || str_contains($f, 'poppins')
            || str_contains($f, 'inter')
            || str_contains($f, 'verdana')
            || str_contains($f, 'tahoma')
            || str_contains($f, 'sans');

        $isMono = str_contains($f, 'courier')
            || str_contains($f, 'consolas')
            || str_contains($f, 'mono');

        if ($this->useUnicodeFonts) {
            if ($isMono) return "'DejaVu Sans Mono', monospace";
            if ($isSans) return "'DejaVu Sans', sans-serif";
            return "'DejaVu Serif', serif";
        }

        if ($isMono) return "Courier, 'DejaVu Sans Mono', monospace";
        if ($isSans) return "Helvetica, Arial, sans-serif";
        return "Times, 'Times New Roman', serif";
    }

    protected function normalizeTextForFont(string $text): string
    {
        if ($this->useUnicodeFonts) {
            return $text;
        }
        // Map common bullet symbols outside WinAnsi to crisp WinAnsi equivalents
        return str_replace(['●', '○', '■', '▪', '▫'], ['•', 'o', '•', '•', '-'], $text);
    }

    protected function containsNonWinAnsi(string $text): bool
    {
        // Fast check: try strict conversion to Windows-1252; if any char cannot be represented, use DejaVu Unicode fonts
        if ($text === '') {
            return false;
        }
        $converted = @iconv('UTF-8', 'windows-1252', $text);
        return $converted === false;
    }
}
