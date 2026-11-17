<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Standalone, zero-dependency Pure-PHP DOCX -> PDF Renderer.
 *
 * Works on ANY PHP 8.2+ server (including restricted shared hosting without LibreOffice,
 * without DomPDF installed, and even without ZipArchive enabled).
 *
 * Directly parses OOXML (document.xml, styles.xml, numbering.xml, header*.xml, footer*.xml,
 * relationships, and embedded media) and renders native PDF 1.4 preserving:
 *  - Exact running headers (including embedded PNG/JPEG logos & colored accent rules)
 *  - Exact running footers with dynamic "Page X of Y" numbering
 *  - Exact Word styles, font families, sizes, colors, bold/italic/underline, superscript/subscript
 *  - Inline badges/shading (e.g. RESEARCH ARTICLE pill), paragraph borders & shading
 *  - Exact tables (borders, cell fills, padding, colspans, multi-page header repeat)
 *  - Embedded figures & diagrams (JPEG native /DCTDecode, PNG via GD /FlateDecode)
 *  - Exact single-column & real line-by-line two-column section flow
 *  - Exact numbered & bulleted lists (e.g. [1], [2] references with hanging indent)
 *  - VML diagonal watermarks when present in header XML
 */
class PureDocxPdfService
{
    protected const RENDERER_VERSION = 'pure_v4';

    protected const W_NS = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
    protected const R_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    protected const A_NS = 'http://schemas.openxmlformats.org/drawingml/2006/main';
    protected const WP_NS = 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing';
    protected const V_NS = 'urn:schemas-microsoft-com:vml';

    // Article/Journal metadata for automatic template placeholder replacement
    protected array $meta = [];

    // ZIP entries map (in-memory or via ZipArchive)
    protected ?\ZipArchive $zipArchive = null;
    protected string $rawZipData = '';
    protected array $zipEntryCache = [];

    // Parsed OOXML metadata
    protected array $styles = [];
    protected array $docDefaultRPr = [];
    protected array $docDefaultPPr = [];
    protected array $numbering = [];
    protected array $listCounters = [];
    protected array $docRels = [];

    // Page geometry (PDF points, origin bottom-left)
    protected float $pageW = 595.28;
    protected float $pageH = 841.89;
    protected float $mLeft = 54.0;
    protected float $mRight = 54.0;
    protected float $mTop = 76.0;
    protected float $mBottom = 52.0;
    protected float $mHeader = 22.0;
    protected float $mFooter = 22.0;

    // Header / Footer / Watermark data
    protected ?array $headerData = null;
    protected ?array $footerData = null;
    protected string $watermarkText = '';

    // Active PDF state
    protected array $pages = [];
    protected int $pageCount = 0;
    protected float $currentY = 0.0;
    protected int $numCols = 1;
    protected int $currentCol = 0;
    protected float $colSpace = 21.25;
    protected float $colTopY = 0.0;
    protected float $minColY = 0.0;

    // PDF Image XObjects: [name => ['w', 'h', 'filter', 'data', 'smask' => ...]]
    protected array $pdfImages = [];
    protected array $mediaToImageKey = [];
    protected int $imageSeq = 0;

    /**
     * Convert a .docx file on disk (or raw .docx binary bytes) to a cached PDF file path.
     * Automatically replaces template placeholders (e.g. [Journal Name], ISSN, Vol/Issue, DOI)
     * with real article/journal metadata passed in $meta.
     */
    public static function convert(string $sourcePathOrBytes, bool $isBinary = false, array $meta = []): ?string
    {
        try {
            $metaHash = !empty($meta) ? ('_' . substr(md5(json_encode($meta)), 0, 10)) : '';

            if ($isBinary) {
                if (empty($sourcePathOrBytes) || !str_starts_with($sourcePathOrBytes, 'PK')) {
                    return null;
                }
                $hash = md5($sourcePathOrBytes) . $metaHash;
                $rawBytes = $sourcePathOrBytes;
                $filePath = null;
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
                $hash = md5($sourcePathOrBytes . '_' . filemtime($sourcePathOrBytes) . '_' . filesize($sourcePathOrBytes)) . $metaHash;
                $rawBytes = '';
                $filePath = $sourcePathOrBytes;
            }

            $cacheDir = storage_path('app/private/articles/pdf');
            if (!is_dir($cacheDir)) {
                @mkdir($cacheDir, 0755, true);
            }
            $cachedPdf = $cacheDir . '/' . self::RENDERER_VERSION . '_' . $hash . '.pdf';

            if (file_exists($cachedPdf) && filesize($cachedPdf) > 1000) {
                return $cachedPdf;
            }

            $renderer = new self();
            $pdfBytes = $renderer->render($filePath, $rawBytes, $meta);

            if ($pdfBytes && str_starts_with($pdfBytes, '%PDF-')) {
                @file_put_contents($cachedPdf, $pdfBytes);
                if (file_exists($cachedPdf) && filesize($cachedPdf) > 1000) {
                    return $cachedPdf;
                }
                $tmpPdf = sys_get_temp_dir() . '/' . self::RENDERER_VERSION . '_' . $hash . '.pdf';
                @file_put_contents($tmpPdf, $pdfBytes);
                if (file_exists($tmpPdf) && filesize($tmpPdf) > 1000) {
                    return $tmpPdf;
                }
            }
        } catch (\Throwable $e) {
            Log::error('PureDocxPdfService error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Render a .docx file or binary bytes directly into PDF 1.4 bytes.
     */
    public function render(?string $filePath, string $rawBytes = '', array $meta = []): ?string
    {
        $this->meta = $meta;

        if (!$this->openZip($filePath, $rawBytes)) {
            return null;
        }

        $docXml = $this->getZipEntry('word/document.xml');
        if ($docXml === null || $docXml === '') {
            $this->closeZip();
            return null;
        }

        $this->parseStyles();
        $this->parseNumbering();
        $this->docRels = $this->parseRels('word/_rels/document.xml.rels');

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($docXml)) {
            $this->closeZip();
            return null;
        }

        $this->replacePlaceholdersInDom($dom);

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::W_NS);
        $body = $xpath->query('//w:body')->item(0);
        if (!$body) {
            $this->closeZip();
            return null;
        }

        $sections = $this->splitIntoSections($body);
        if (empty($sections)) {
            $this->closeZip();
            return null;
        }

        // Page geometry from primary section
        $primarySect = end($sections)['sectPr'] ?? [];
        $this->pageW = (float) ($primarySect['pageW'] ?? 595.28);
        $this->pageH = (float) ($primarySect['pageH'] ?? 841.89);
        $this->mLeft = (float) ($primarySect['mLeft'] ?? 54.0);
        $this->mRight = (float) ($primarySect['mRight'] ?? 54.0);
        $this->mTop = (float) ($primarySect['mTop'] ?? 65.0);
        $this->mBottom = (float) ($primarySect['mBottom'] ?? 55.0);
        $this->mHeader = (float) ($primarySect['mHeader'] ?? 22.0);
        $this->mFooter = (float) ($primarySect['mFooter'] ?? 22.0);

        // Load running header & footer XML parts
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

        if ($headerRefId && isset($this->docRels[$headerRefId])) {
            $this->loadHeaderPart($this->normalizeWordTarget($this->docRels[$headerRefId]['target']));
        }
        if ($footerRefId && isset($this->docRels[$footerRefId])) {
            $this->loadFooterPart($this->normalizeWordTarget($this->docRels[$footerRefId]['target']));
        }

        // Ensure body margins clear header and footer cleanly
        if ($this->headerData !== null) {
            $this->mTop = max($this->mTop, $this->mHeader + $this->headerData['height'] + 10.0);
        }
        if ($this->footerData !== null) {
            $this->mBottom = max($this->mBottom, $this->mFooter + 24.0);
        }

        // Start Page 1
        $this->addPage();

        // Render all sections in document order
        foreach ($sections as $secIdx => $section) {
            $cols = max(1, (int) ($section['sectPr']['cols'] ?? 1));
            $colSpace = (float) ($section['sectPr']['colSpace'] ?? 21.25);
            $breakType = $section['sectPr']['type'] ?? 'nextPage';

            if ($secIdx > 0 && $breakType !== 'continuous') {
                $this->numCols = $cols;
                $this->colSpace = $colSpace;
                $this->currentCol = 0;
                $this->addPage();
            } else {
                // Continuous transition (e.g. 1-col title/abstract -> 2-col body, or 2-col -> 1-col wide figure -> 2-col)
                if ($this->numCols !== $cols) {
                    $this->minColY = min($this->minColY, $this->currentY);
                    if ($this->numCols >= 2 && $cols === 1) {
                        // Leaving 2-col for 1-col: ALWAYS move strictly below lowest column (or new page)
                        $this->currentY = $this->minColY - 6.0;
                        if ($this->currentY < ($this->mBottom + 40.0)) {
                            $this->addPage();
                        }
                    } elseif ($this->numCols === 1 && $cols >= 2) {
                        if ($this->currentY < ($this->mBottom + 40.0)) {
                            $this->addPage();
                        }
                    }
                    $this->numCols = $cols;
                    $this->colSpace = $colSpace;
                    $this->currentCol = 0;
                    $this->colTopY = $this->currentY;
                    $this->minColY = $this->currentY;
                }
            }

            foreach ($section['nodes'] as $node) {
                if ($node->nodeType !== XML_ELEMENT_NODE) continue;
                if ($node->localName === 'p') {
                    $this->renderParagraphNode($node, $this->docRels);
                } elseif ($node->localName === 'tbl') {
                    $this->renderTableNode($node, $this->docRels);
                }
            }
        }

        $this->closeZip();

        return $this->compilePdf();
    }

    // =========================================================================
    // PAGE & COLUMN FLOW MANAGEMENT
    // =========================================================================

    protected function contentWidth(): float
    {
        return $this->pageW - $this->mLeft - $this->mRight;
    }

    protected function activeColumnWidth(): float
    {
        if ($this->numCols >= 2) {
            return ($this->contentWidth() - $this->colSpace) / 2.0;
        }
        return $this->contentWidth();
    }

    protected function activeColumnLeftX(): float
    {
        if ($this->numCols >= 2 && $this->currentCol === 1) {
            return $this->mLeft + $this->activeColumnWidth() + $this->colSpace;
        }
        return $this->mLeft;
    }

    protected function addPage(): void
    {
        $this->pageCount++;
        $this->pages[$this->pageCount] = '';
        $this->currentCol = 0;
        $this->currentY = $this->pageH - $this->mTop;
        $this->colTopY = $this->currentY;
        $this->minColY = $this->currentY;

        // Draw watermark first (behind content)
        if ($this->watermarkText !== '') {
            $this->drawWatermark();
        }

        // Draw running header
        if ($this->headerData !== null) {
            $this->drawRunningHeader();
        }
    }

    /**
     * Record the lowest vertical Y reached on the current page across all columns.
     */
    protected function recordMinY(): void
    {
        if ($this->currentY < $this->minColY) {
            $this->minColY = $this->currentY;
        }
    }

    /**
     * Position cursor safely for a full-width element (wide figure or wide table) inside a 2-col section:
     * NEVER jumps into right column; moves strictly below all existing 2-col text or starts a fresh page.
     */
    protected function prepareFullWidthBlock(float $neededHeight): void
    {
        $this->recordMinY();
        $this->currentCol = 0;
        $this->currentY = $this->minColY - 6.0;

        if (($this->currentY - $neededHeight) < $this->mBottom) {
            $this->addPage();
        }

        $this->colTopY = $this->currentY;
        $this->minColY = $this->currentY;
    }

    /**
     * Ensure at least $neededHeight pt remain in the active column; otherwise advance
     * to the right column (if in 2-col left column) or start a new page.
     */
    protected function ensureSpace(float $neededHeight): void
    {
        $this->recordMinY();

        if (($this->currentY - $neededHeight) >= $this->mBottom) {
            return;
        }

        if ($this->numCols >= 2 && $this->currentCol === 0) {
            $this->currentCol = 1;
            $this->currentY = $this->colTopY;
            if (($this->currentY - $neededHeight) < $this->mBottom) {
                $this->addPage();
            }
            return;
        }

        $this->addPage();
    }

    protected function append(string $cmd): void
    {
        $this->pages[$this->pageCount] .= $cmd . "\n";
    }

    // =========================================================================
    // AUTOMATIC TEMPLATE PLACEHOLDER REPLACEMENT ([Journal Name], ISSN, VOL/ISSUE, DOI)
    // =========================================================================

    protected function replacePlaceholdersInDom(\DOMDocument $dom): void
    {
        if (empty($this->meta)) {
            return;
        }

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::W_NS);

        // Pass 1: Direct replacement inside individual <w:t> nodes (preserves all per-run formatting)
        foreach ($xpath->query('//w:t') as $tNode) {
            $orig = $tNode->textContent;
            if ($orig === '') continue;
            $updated = $this->applyMetadataPlaceholders($orig);
            if ($updated !== $orig) {
                $tNode->textContent = $updated;
            }
        }

        // Pass 2: Handle placeholders that Word split across multiple adjacent <w:t> runs in a paragraph
        foreach ($xpath->query('//w:p') as $pNode) {
            $tNodes = $xpath->query('.//w:t', $pNode);
            if (!$tNodes || $tNodes->length < 2) continue;

            $combined = '';
            foreach ($tNodes as $tn) {
                $combined .= $tn->textContent;
            }
            $updated = $this->applyMetadataPlaceholders($combined);
            if ($updated !== $combined) {
                // Only collapse runs in paragraphs where a split placeholder existed
                $first = true;
                foreach ($tNodes as $tn) {
                    if ($first) {
                        $tn->textContent = $updated;
                        $first = false;
                    } else {
                        $tn->textContent = '';
                    }
                }
            }
        }
    }

    protected function applyMetadataPlaceholders(string $text): string
    {
        if (empty($this->meta) || $text === '') {
            return $text;
        }

        // 1. Replace [Journal Name] placeholder (any casing / whitespace)
        if (!empty($this->meta['journal_title'])) {
            $text = preg_replace('/\[\s*Journal\s+Name\s*\]/i', $this->meta['journal_title'], $text);
        }

        // 2. Replace template ISSN placeholder (XXXX-XXXX) ONLY if still placeholder
        if (!empty($this->meta['issn']) && stripos($text, 'XXXX') !== false) {
            $issn = $this->meta['issn'];
            if (stripos($issn, 'online') !== false || stripos($issn, 'applied') !== false) {
                $text = preg_replace('/ISSN:\s*XXXX-XXXX\s*\(Online\)/i', 'ISSN: ' . $issn, $text);
            } else {
                $text = preg_replace('/XXXX-XXXX/i', $issn, $text);
            }
        }

        // 3. Replace template Vol. X, Issue X placeholder ONLY if still placeholder
        if (!empty($this->meta['volume']) && !empty($this->meta['issue'])) {
            $vol = $this->meta['volume'];
            $iss = $this->meta['issue'];
            $year = $this->meta['year'] ?? date('Y');
            $text = preg_replace('/Vol\.\s*X,\s*Issue\s*X,\s*\d{4},\s*pp\.\s*XX[\x{2013}\x{2014}-]XX/iu', "Vol. {$vol}, Issue {$iss} ({$year})", $text);
            $text = preg_replace('/Vol\.\s*X,\s*Issue\s*X/i', "Vol. {$vol}, Issue {$iss}", $text);
        }

        // 4. Replace template DOI placeholder (10.XXXXX/hjparam.XXXX) ONLY if still placeholder
        if (!empty($this->meta['doi']) && stripos($text, 'XXXXX') !== false) {
            $text = preg_replace('/10\.X+\/hjparam\.X+/i', $this->meta['doi'], $text);
        }

        return $text;
    }

    // =========================================================================
    // RUNNING HEADER, FOOTER & WATERMARK
    // =========================================================================

    protected function loadHeaderPart(string $zipPath): void
    {
        $xml = $this->getZipEntry($zipPath);
        if (!$xml) return;

        $rels = $this->parseRels('word/_rels/' . basename($zipPath) . '.rels');
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) return;

        // Automatically replace [Journal Name] and template metadata placeholders in header
        $this->replacePlaceholdersInDom($dom);

        // Extract optional VML watermark
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('v', self::V_NS);
        $tp = $xpath->query('.//v:textpath')->item(0);
        if ($tp instanceof \DOMElement) {
            $wm = trim($tp->getAttribute('string'));
            if ($wm !== '') $this->watermarkText = $wm;
        }

        $root = $dom->documentElement;
        if (!$root) return;

        // Estimate header height by parsing its nodes
        $items = [];
        $estH = 0.0;
        $cw = $this->contentWidth();
        foreach ($root->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            if ($c->localName === 'tbl') {
                $tblModel = $this->buildTableModel($c, $rels, $cw, true);
                if ($tblModel) {
                    $items[] = ['type' => 'tbl', 'model' => $tblModel];
                    $estH += $tblModel['totalHeight'];
                }
            } elseif ($c->localName === 'p') {
                $pModel = $this->buildParagraphModel($c, $rels, $cw, true);
                if ($pModel) {
                    $items[] = ['type' => 'p', 'model' => $pModel];
                    $estH += $pModel['totalHeight'];
                }
            }
        }

        if (!empty($items)) {
            $this->headerData = [
                'items' => $items,
                'height' => max(24.0, $estH),
            ];
        }
    }

    protected function drawRunningHeader(): void
    {
        if (!$this->headerData) return;

        $savedY = $this->currentY;
        $savedCol = $this->currentCol;
        $savedCols = $this->numCols;

        $this->numCols = 1;
        $this->currentCol = 0;
        $y = $this->pageH - $this->mHeader;

        foreach ($this->headerData['items'] as $item) {
            if ($item['type'] === 'tbl') {
                $y = $this->drawTableModelAt($item['model'], $this->mLeft, $y);
            } elseif ($item['type'] === 'p') {
                $y = $this->drawParagraphModelAt($item['model'], $this->mLeft, $y, $this->contentWidth());
            }
        }

        $this->numCols = $savedCols;
        $this->currentCol = $savedCol;
        $this->currentY = $savedY;
    }

    protected function loadFooterPart(string $zipPath): void
    {
        $xml = $this->getZipEntry($zipPath);
        if (!$xml) return;

        $rels = $this->parseRels('word/_rels/' . basename($zipPath) . '.rels');
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) return;

        $this->replacePlaceholdersInDom($dom);

        $root = $dom->documentElement;
        if (!$root) return;

        $borderColor = null;
        $borderWidth = 0.75;
        $segments = [[], [], []];
        $hasPageField = false;

        foreach ($root->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE || $child->localName !== 'p') continue;

            $pPr = [];
            $inheritedRPr = $this->docDefaultRPr;
            foreach ($child->childNodes as $pc) {
                if ($pc->nodeType === XML_ELEMENT_NODE && $pc->localName === 'pPr') {
                    $pPr = $this->parsePPr($pc);
                    if (!empty($pPr['borders']['top'])) {
                        $borderColor = $pPr['borders']['top']['color'];
                        $borderWidth = $pPr['borders']['top']['width'];
                    }
                    break;
                }
            }

            $segIdx = 0;
            $inField = false;
            $fieldInstr = '';

            $processRun = function (\DOMElement $r) use (&$segments, &$segIdx, &$inField, &$fieldInstr, &$hasPageField, $inheritedRPr) {
                $rPr = $inheritedRPr;
                foreach ($r->childNodes as $rc) {
                    if ($rc->nodeType === XML_ELEMENT_NODE && $rc->localName === 'rPr') {
                        $rPr = array_merge($inheritedRPr, $this->parseRPr($rc));
                    }
                }
                foreach ($r->childNodes as $rc) {
                    if ($rc->nodeType !== XML_ELEMENT_NODE) continue;
                    $ln = $rc->localName;
                    if ($ln === 'fldChar') {
                        $ft = $rc->getAttributeNS(self::W_NS, 'fldCharType');
                        if ($ft === 'begin') { $inField = true; $fieldInstr = ''; }
                        elseif ($ft === 'end') {
                            if (stripos($fieldInstr, 'PAGE') !== false || stripos($fieldInstr, 'NUMPAGES') !== false) {
                                $hasPageField = true;
                            }
                            $inField = false; $fieldInstr = '';
                        }
                    } elseif ($ln === 'instrText') {
                        $fieldInstr .= $rc->textContent;
                        if (stripos($fieldInstr, 'PAGE') !== false || stripos($fieldInstr, 'NUMPAGES') !== false) {
                            $hasPageField = true;
                        }
                    } elseif ($ln === 'tab') {
                        $segIdx = min(2, $segIdx + 1);
                    } elseif ($ln === 't' && !$inField) {
                        $txt = $rc->textContent;
                        if (str_contains($txt, "\t")) {
                            $parts = explode("\t", $txt);
                            foreach ($parts as $pi => $pt) {
                                if ($pi > 0) $segIdx = min(2, $segIdx + 1);
                                if ($pt !== '') {
                                    $segments[$segIdx][] = ['text' => $pt, 'rPr' => $rPr];
                                }
                            }
                        } elseif ($txt !== '') {
                            $segments[$segIdx][] = ['text' => $txt, 'rPr' => $rPr];
                        }
                    }
                }
            };

            foreach ($child->childNodes as $pc) {
                if ($pc->nodeType !== XML_ELEMENT_NODE) continue;
                if ($pc->localName === 'r') {
                    $processRun($pc);
                } elseif ($pc->localName === 'hyperlink') {
                    foreach ($pc->childNodes as $hc) {
                        if ($hc->nodeType === XML_ELEMENT_NODE && $hc->localName === 'r') {
                            $processRun($hc);
                        }
                    }
                }
            }
            break; // primary footer paragraph
        }

        $this->footerData = [
            'borderColor' => $borderColor,
            'borderWidth' => $borderWidth,
            'segments' => $segments,
            'hasPageField' => $hasPageField,
        ];
    }

    protected function buildFooterStream(int $pageNum, int $totalPages): string
    {
        if (!$this->footerData) {
            return '';
        }

        $out = '';
        $y = $this->mFooter;
        $xLeft = $this->mLeft;
        $xRight = $this->pageW - $this->mRight;

        // Top border line
        if (!empty($this->footerData['borderColor'])) {
            [$r, $g, $b] = $this->hexToRgb($this->footerData['borderColor']);
            $w = (float) ($this->footerData['borderWidth'] ?? 0.75);
            $out .= sprintf("%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S\n", $w, $r, $g, $b, $xLeft, $y + 11.0, $xRight, $y + 11.0);
        }

        $segments = $this->footerData['segments'];

        // Left segment
        if (!empty($segments[0])) {
            $cursorX = $xLeft;
            foreach ($segments[0] as $run) {
                $font = $this->pickPdfFont($run['rPr']);
                $size = (float) ($run['rPr']['size'] ?? 7.5);
                [$r, $g, $b] = $this->hexToRgb($run['rPr']['color'] ?? '555555');
                $out .= $this->pdfTextCmd($cursorX, $y, $run['text'], $font, $size, $r, $g, $b);
                $cursorX += $this->measureString($run['text'], $font, $size);
            }
        }

        // Center segment
        if (!empty($segments[1])) {
            $totalW = 0.0;
            foreach ($segments[1] as $run) {
                $totalW += $this->measureString($run['text'], $this->pickPdfFont($run['rPr']), (float) ($run['rPr']['size'] ?? 7.5));
            }
            $cursorX = ($this->pageW - $totalW) / 2.0;
            foreach ($segments[1] as $run) {
                $font = $this->pickPdfFont($run['rPr']);
                $size = (float) ($run['rPr']['size'] ?? 7.5);
                [$r, $g, $b] = $this->hexToRgb($run['rPr']['color'] ?? '555555');
                $out .= $this->pdfTextCmd($cursorX, $y, $run['text'], $font, $size, $r, $g, $b);
                $cursorX += $this->measureString($run['text'], $font, $size);
            }
        }

        // Right segment (dynamic Page X of Y if page field present)
        if (!empty($this->footerData['hasPageField'])) {
            $size = 7.5;
            $p1 = 'Page ';
            $p2 = (string) $pageNum;
            $p3 = ' of ' . (string) $totalPages;
            $w1 = $this->measureString($p1, 'F1', $size);
            $w2 = $this->measureString($p2, 'F2', $size);
            $w3 = $this->measureString($p3, 'F1', $size);
            $sx = $xRight - ($w1 + $w2 + $w3);
            $out .= $this->pdfTextCmd($sx, $y, $p1, 'F1', $size, 0.333, 0.333, 0.333);
            $out .= $this->pdfTextCmd($sx + $w1, $y, $p2, 'F2', $size, 0.004, 0.267, 0.545);
            $out .= $this->pdfTextCmd($sx + $w1 + $w2, $y, $p3, 'F1', $size, 0.333, 0.333, 0.333);
        } elseif (!empty($segments[2])) {
            $totalW = 0.0;
            foreach ($segments[2] as $run) {
                $totalW += $this->measureString($run['text'], $this->pickPdfFont($run['rPr']), (float) ($run['rPr']['size'] ?? 7.5));
            }
            $cursorX = $xRight - $totalW;
            foreach ($segments[2] as $run) {
                $font = $this->pickPdfFont($run['rPr']);
                $size = (float) ($run['rPr']['size'] ?? 7.5);
                [$r, $g, $b] = $this->hexToRgb($run['rPr']['color'] ?? '555555');
                $out .= $this->pdfTextCmd($cursorX, $y, $run['text'], $font, $size, $r, $g, $b);
                $cursorX += $this->measureString($run['text'], $font, $size);
            }
        }

        return $out;
    }

    protected function drawWatermark(): void
    {
        $wm = strtoupper(substr($this->watermarkText, 0, 40));
        $this->append("q");
        $this->append("0.92 0.93 0.94 rg");
        $this->append("0.7071 0.7071 -0.7071 0.7071 140 250 cm");
        $this->append(sprintf("BT /F2 44.0 Tf (%s) Tj ET", $this->pdfEscape($wm)));
        $this->append("Q");
    }

    // =========================================================================
    // PARAGRAPH PARSING, RICH WRAPPING & RENDERING
    // =========================================================================

    protected function renderParagraphNode(\DOMElement $p, array $rels): void
    {
        $maxW = $this->activeColumnWidth();
        $model = $this->buildParagraphModel($p, $rels, $maxW, false);
        if ($model === null) {
            return;
        }

        // If paragraph contains a wide image exceeding 1 column in 2-col mode, span full width safely
        if ($this->numCols >= 2 && !empty($model['maxImageNaturalW']) && $model['maxImageNaturalW'] > ($maxW * 1.15)) {
            $fullW = $this->contentWidth();
            $model = $this->buildParagraphModel($p, $rels, $fullW, false);
            if ($model === null) return;

            $this->prepareFullWidthBlock($model['totalHeight']);
            $this->currentY = $this->drawParagraphModelAt($model, $this->mLeft, $this->currentY, $fullW);
            $this->currentCol = 0;
            $this->colTopY = $this->currentY;
            $this->minColY = $this->currentY;
            return;
        }

        $spaceBefore = $model['spaceBefore'];
        $spaceAfter = $model['spaceAfter'];

        // Avoid orphan heading at bottom of column
        if (!empty($model['isHeading'])) {
            $this->ensureSpace($spaceBefore + $model['contentHeight'] + 32.0);
        } elseif (count($model['lines']) <= 2) {
            $this->ensureSpace($model['totalHeight']);
        }

        // Apply spaceBefore (only if not at very top of column)
        if ($this->currentY < $this->colTopY - 1.0) {
            $this->currentY -= $spaceBefore;
        }

        // Draw empty border-only paragraph (accent divider rule)
        if (empty($model['lines']) && !empty($model['borders'])) {
            $this->ensureSpace(4.0);
            $x = $this->activeColumnLeftX();
            $this->drawParagraphBorders($model['borders'], $x, $this->currentY, $this->currentY - 2.0, $maxW);
            $this->currentY -= (2.0 + $spaceAfter);
            return;
        }

        // Draw line by line (supports exact cross-column and cross-page flowing!)
        $lineCount = count($model['lines']);
        foreach ($model['lines'] as $lIdx => $line) {
            $this->ensureSpace($line['height']);
            $colX = $this->activeColumnLeftX();
            $colW = $this->activeColumnWidth();
            $isLastLine = ($lIdx === $lineCount - 1) || !empty($line['hardBreak']);

            // Draw list marker on line 0
            if ($lIdx === 0 && !empty($model['listMarker'])) {
                $mFont = $model['listFont'] ?? 'F4';
                $mSize = $model['listSize'] ?? 8.5;
                $mX = $colX + max(0.0, $model['indLeft'] - $model['hanging']);
                $baseY = $this->currentY - ($line['ascent']);
                $this->append($this->pdfTextCmd($mX, $baseY, $model['listMarker'], $mFont, $mSize, 0.1, 0.1, 0.1));
            }

            $this->drawRichLine($line, $colX, $this->currentY, $colW, $model, $lIdx === 0, $isLastLine);
            $this->currentY -= $line['height'];
            $this->recordMinY();
        }

        $this->currentY -= $spaceAfter;
        $this->recordMinY();
    }

    /**
     * Build a pre-measured paragraph model (lines, images, borders, shading, height).
     */
    protected function buildParagraphModel(\DOMElement $p, array $rels, float $containerW, bool $isHeaderFooter = false): ?array
    {
        $pPrNode = null;
        foreach ($p->childNodes as $c) {
            if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'pPr') {
                $pPrNode = $c;
                break;
            }
        }

        $styleId = null;
        $directPPr = [];
        $directPRPr = [];
        if ($pPrNode) {
            foreach ($pPrNode->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE) continue;
                if ($c->localName === 'pStyle') $styleId = $c->getAttributeNS(self::W_NS, 'val');
                elseif ($c->localName === 'rPr') $directPRPr = $this->parseRPr($c);
            }
            $directPPr = $this->parsePPr($pPrNode);
        }

        $resolved = $this->resolveStyle($styleId);
        $pPr = array_merge($this->docDefaultPPr, $resolved['pPr'], $directPPr);
        $inheritedRPr = array_merge($this->docDefaultRPr, $resolved['rPr'], $directPRPr);

        // List numbering marker
        $listMarker = null;
        $indLeft = (float) ($pPr['indLeft'] ?? 0.0);
        $indRight = (float) ($pPr['indRight'] ?? 0.0);
        $firstLine = (float) ($pPr['firstLine'] ?? 0.0);
        $hanging = (float) ($pPr['hanging'] ?? 0.0);

        if (!$isHeaderFooter && !empty($pPr['numId'])) {
            $lInfo = $this->getListMarker($pPr['numId'], (int) ($pPr['ilvl'] ?? 0));
            if ($lInfo !== null) {
                $listMarker = $lInfo['marker'];
                $indLeft = max($indLeft, $lInfo['indentLeft']);
                $hanging = max($hanging, $lInfo['hanging']);
                $firstLine = 0.0;
            }
        }

        // Tokenize all child runs/hyperlinks/math
        $tokens = [];
        $maxImageNaturalW = 0.0;
        $this->collectParagraphTokens($p, $inheritedRPr, $rels, $containerW - $indLeft - $indRight, $tokens, $maxImageNaturalW);

        $hasBorders = !empty($pPr['borders']);
        $hasShading = !empty($pPr['shd']);

        if (empty($tokens) && !$hasBorders && !$hasShading) {
            return null;
        }

        $spaceBefore = (float) ($pPr['spaceBefore'] ?? 0.0);
        $spaceAfter = (float) ($pPr['spaceAfter'] ?? ($isHeaderFooter ? 0.0 : 3.0));
        $lineRatio = (float) ($pPr['lineRatio'] ?? 1.18);
        $align = $pPr['align'] ?? 'left';

        // Wrap tokens into measured lines
        $lines = $this->wrapTokensIntoLines($tokens, $containerW, $indLeft, $indRight, $firstLine, $lineRatio);

        $contentH = 0.0;
        foreach ($lines as $ln) {
            $contentH += $ln['height'];
        }
        if (empty($lines) && $hasBorders) {
            $contentH = 2.0;
        }

        $isHeading = !empty($pPr['keepNext'])
            || ($styleId && stripos($styleId, 'Heading') !== false)
            || ($styleId && stripos($styleId, 'Title') !== false);

        return [
            'lines' => $lines,
            'align' => $align,
            'indLeft' => $indLeft,
            'indRight' => $indRight,
            'firstLine' => $firstLine,
            'hanging' => $hanging,
            'spaceBefore' => $spaceBefore,
            'spaceAfter' => $spaceAfter,
            'borders' => $pPr['borders'] ?? [],
            'shd' => $pPr['shd'] ?? null,
            'listMarker' => $listMarker,
            'listFont' => $this->pickPdfFont($inheritedRPr),
            'listSize' => (float) ($inheritedRPr['size'] ?? 8.5),
            'isHeading' => $isHeading,
            'maxImageNaturalW' => $maxImageNaturalW,
            'contentHeight' => $contentH,
            'totalHeight' => $spaceBefore + $contentH + $spaceAfter,
        ];
    }

    /**
     * Draw a pre-measured paragraph model at fixed coordinates (used inside table cells and headers).
     */
    protected function drawParagraphModelAt(array $model, float $x, float $y, float $width): float
    {
        $y -= $model['spaceBefore'];
        $startY = $y;

        if (!empty($model['shd']) && $model['contentHeight'] > 0) {
            [$r, $g, $b] = $this->hexToRgb($model['shd']);
            $this->append(sprintf("%.3F %.3F %.3F rg %.2F %.2F %.2F %.2F re f", $r, $g, $b, $x, $y - $model['contentHeight'], $width, $model['contentHeight']));
        }

        if (empty($model['lines']) && !empty($model['borders'])) {
            $this->drawParagraphBorders($model['borders'], $x, $y, $y - 2.0, $width);
            return $y - 2.0 - $model['spaceAfter'];
        }

        $lineCount = count($model['lines']);
        foreach ($model['lines'] as $lIdx => $line) {
            $isLastLine = ($lIdx === $lineCount - 1) || !empty($line['hardBreak']);
            $this->drawRichLine($line, $x, $y, $width, $model, $lIdx === 0, $isLastLine);
            $y -= $line['height'];
        }

        if (!empty($model['borders'])) {
            $this->drawParagraphBorders($model['borders'], $x, $startY, $y, $width);
        }

        return $y - $model['spaceAfter'];
    }

    protected function drawParagraphBorders(array $borders, float $x, float $topY, float $botY, float $width): void
    {
        foreach ($borders as $side => $b) {
            if (empty($b) || $b['style'] === 'none') continue;
            [$r, $g, $bCol] = $this->hexToRgb($b['color']);
            $bw = (float) $b['width'];
            if ($side === 'bottom') {
                $this->append(sprintf("%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S", $bw, $r, $g, $bCol, $x, $botY, $x + $width, $botY));
            } elseif ($side === 'top') {
                $this->append(sprintf("%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S", $bw, $r, $g, $bCol, $x, $topY, $x + $width, $topY));
            } elseif ($side === 'left') {
                $this->append(sprintf("%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S", $bw, $r, $g, $bCol, $x, $topY, $x, $botY));
            } elseif ($side === 'right') {
                $this->append(sprintf("%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S", $bw, $r, $g, $bCol, $x + $width, $topY, $x + $width, $botY));
            }
        }
    }

    /**
     * Collect inline word/space/image/break tokens from a <w:p> element.
     */
    protected function collectParagraphTokens(\DOMElement $p, array $inheritedRPr, array $rels, float $maxW, array &$tokens, float &$maxImageNaturalW): void
    {
        $processRun = function (\DOMElement $r) use ($inheritedRPr, $rels, $maxW, &$tokens, &$maxImageNaturalW) {
            $directRPr = [];
            foreach ($r->childNodes as $c) {
                if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'rPr') {
                    $directRPr = $this->parseRPr($c);
                    break;
                }
            }
            $charRPr = !empty($directRPr['rStyle']) ? $this->resolveStyle($directRPr['rStyle'])['rPr'] : [];
            $rPr = array_merge($inheritedRPr, $charRPr, $directRPr);

            $font = $this->pickPdfFont($rPr);
            $baseSize = (float) ($rPr['size'] ?? 10.0);
            $vertAlign = $rPr['vertAlign'] ?? null;
            $size = ($vertAlign === 'superscript' || $vertAlign === 'subscript') ? ($baseSize * 0.66) : $baseSize;
            $color = $rPr['color'] ?? '111111';
            $bg = $rPr['shd'] ?? null;
            $underline = !empty($rPr['underline']);

            $skipField = false;
            foreach ($r->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE) continue;
                $ln = $c->localName;
                if ($ln === 'fldChar') {
                    $ft = $c->getAttributeNS(self::W_NS, 'fldCharType');
                    if ($ft === 'begin') $skipField = true;
                    elseif ($ft === 'end') $skipField = false;
                } elseif ($ln === 'br') {
                    $tokens[] = ['type' => 'break', 'pageBreak' => ($c->getAttributeNS(self::W_NS, 'type') === 'page')];
                } elseif ($ln === 'tab') {
                    $tokens[] = [
                        'type' => 'space',
                        'text' => '    ',
                        'font' => $font,
                        'size' => $size,
                        'baseSize' => $baseSize,
                        'width' => $this->measureString('    ', $font, $size),
                        'color' => $color,
                        'bg' => null,
                        'vertAlign' => null,
                        'underline' => false,
                    ];
                } elseif ($ln === 't' && !$skipField) {
                    $txt = $c->textContent;
                    if ($txt === '') continue;
                    if (!empty($rPr['caps'])) {
                        $txt = mb_strtoupper($txt, 'UTF-8');
                    }

                    // Keep inline shaded badges (like " RESEARCH ARTICLE ") together as a single visual pill token
                    if ($bg !== null) {
                        $padW = 4.0;
                        $tokens[] = [
                            'type' => 'word',
                            'text' => $txt,
                            'font' => $font,
                            'size' => $size,
                            'baseSize' => $baseSize,
                            'width' => $this->measureString($txt, $font, $size) + $padW,
                            'color' => $color,
                            'bg' => $bg,
                            'vertAlign' => $vertAlign,
                            'underline' => $underline,
                            'isBadge' => true,
                        ];
                        continue;
                    }

                    // Split into word and whitespace runs so justification works cleanly
                    $parts = preg_split('/(\s+)/u', $txt, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                    foreach ($parts as $part) {
                        if (preg_match('/^\s+$/u', $part)) {
                            $spText = str_contains($part, "\t") ? '    ' : ' ';
                            $tokens[] = [
                                'type' => 'space',
                                'text' => $spText,
                                'font' => $font,
                                'size' => $size,
                                'baseSize' => $baseSize,
                                'width' => $this->measureString($spText, $font, $size),
                                'color' => $color,
                                'bg' => null,
                                'vertAlign' => null,
                                'underline' => false,
                            ];
                        } else {
                            // Break excessively long unbroken words (e.g. long URLs/DOIs) to fit column width
                            $w = $this->measureString($part, $font, $size);
                            if ($w > $maxW && $maxW > 40.0) {
                                foreach ($this->chunkLongWord($part, $font, $size, $maxW) as $chunk) {
                                    $tokens[] = [
                                        'type' => 'word',
                                        'text' => $chunk,
                                        'font' => $font,
                                        'size' => $size,
                                        'baseSize' => $baseSize,
                                        'width' => $this->measureString($chunk, $font, $size),
                                        'color' => $color,
                                        'bg' => null,
                                        'vertAlign' => $vertAlign,
                                        'underline' => $underline,
                                    ];
                                }
                            } else {
                                $tokens[] = [
                                    'type' => 'word',
                                    'text' => $part,
                                    'font' => $font,
                                    'size' => $size,
                                    'baseSize' => $baseSize,
                                    'width' => $w,
                                    'color' => $color,
                                    'bg' => null,
                                    'vertAlign' => $vertAlign,
                                    'underline' => $underline,
                                ];
                            }
                        }
                    }
                } elseif ($ln === 'drawing' || $ln === 'pict') {
                    $imgInfo = $this->registerDrawingImage($c, $rels, $maxW);
                    if ($imgInfo !== null) {
                        if ($imgInfo['naturalW'] > $maxImageNaturalW) {
                            $maxImageNaturalW = $imgInfo['naturalW'];
                        }
                        $tokens[] = [
                            'type' => 'image',
                            'key' => $imgInfo['key'],
                            'width' => $imgInfo['w'],
                            'height' => $imgInfo['h'],
                        ];
                    }
                }
            }
        };

        foreach ($p->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) continue;
            if ($child->localName === 'r') {
                $processRun($child);
            } elseif ($child->localName === 'hyperlink') {
                foreach ($child->childNodes as $hc) {
                    if ($hc->nodeType === XML_ELEMENT_NODE && $hc->localName === 'r') {
                        $processRun($hc);
                    }
                }
            } elseif ($child->localName === 'oMath' || $child->localName === 'oMathPara') {
                $mt = trim($child->textContent);
                if ($mt !== '') {
                    $sz = (float) ($inheritedRPr['size'] ?? 10.0);
                    $tokens[] = [
                        'type' => 'word',
                        'text' => $mt,
                        'font' => 'F6',
                        'size' => $sz,
                        'baseSize' => $sz,
                        'width' => $this->measureString($mt, 'F6', $sz),
                        'color' => '111111',
                        'bg' => null,
                        'vertAlign' => null,
                        'underline' => false,
                    ];
                }
            }
        }
    }

    protected function chunkLongWord(string $word, string $font, float $size, float $maxW): array
    {
        $chunks = [];
        $cur = '';
        $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $ch) {
            if ($cur !== '' && $this->measureString($cur . $ch, $font, $size) > $maxW) {
                $chunks[] = $cur;
                $cur = $ch;
            } else {
                $cur .= $ch;
            }
        }
        if ($cur !== '') $chunks[] = $cur;
        return $chunks;
    }

    /**
     * Wrap rich tokens into measured lines respecting firstLine indent, left/right indents, and images.
     */
    protected function wrapTokensIntoLines(array $tokens, float $containerW, float $indLeft, float $indRight, float $firstLine, float $lineRatio): array
    {
        $lines = [];
        $cur = [];
        $curW = 0.0;
        $lineIdx = 0;

        $availForLine = function (int $idx) use ($containerW, $indLeft, $indRight, $firstLine): float {
            return max(20.0, $containerW - $indLeft - $indRight - ($idx === 0 ? $firstLine : 0.0));
        };

        $finalizeLine = function (array $items, bool $hardBreak = false) use ($lineRatio): array {
            // Trim trailing spaces
            while (!empty($items) && end($items)['type'] === 'space') {
                array_pop($items);
            }
            $totalW = 0.0;
            $maxFont = 8.0;
            $maxImgH = 0.0;
            $spaceCount = 0;
            foreach ($items as $it) {
                $totalW += $it['width'];
                if ($it['type'] === 'image') {
                    if ($it['height'] > $maxImgH) $maxImgH = $it['height'];
                } else {
                    $bs = (float) ($it['baseSize'] ?? $it['size'] ?? 10.0);
                    if ($bs > $maxFont) $maxFont = $bs;
                    if ($it['type'] === 'space') $spaceCount++;
                }
            }
            $textH = $maxFont * max(1.12, $lineRatio);
            $height = max($textH, $maxImgH > 0 ? ($maxImgH + 3.0) : 0.0);
            return [
                'items' => $items,
                'width' => $totalW,
                'height' => $height,
                'ascent' => $maxImgH > $textH ? $maxImgH : ($maxFont * 0.85),
                'spaceCount' => $spaceCount,
                'hardBreak' => $hardBreak,
            ];
        };

        foreach ($tokens as $tok) {
            if ($tok['type'] === 'break') {
                if (!empty($cur)) {
                    $lines[] = $finalizeLine($cur, true);
                    $cur = [];
                    $curW = 0.0;
                    $lineIdx++;
                }
                continue;
            }

            // Skip leading spaces on a new line
            if (empty($cur) && $tok['type'] === 'space') {
                continue;
            }

            $avail = $availForLine($lineIdx);

            // Standalone large image gets its own line
            if ($tok['type'] === 'image' && $tok['height'] > 24.0) {
                if (!empty($cur)) {
                    $lines[] = $finalizeLine($cur, true);
                    $cur = [];
                    $curW = 0.0;
                    $lineIdx++;
                }
                $lines[] = $finalizeLine([$tok], true);
                $lineIdx++;
                continue;
            }

            if (!empty($cur) && ($curW + $tok['width']) > $avail) {
                $lines[] = $finalizeLine($cur, false);
                $cur = [];
                $curW = 0.0;
                $lineIdx++;
                if ($tok['type'] === 'space') {
                    continue;
                }
            }

            $cur[] = $tok;
            $curW += $tok['width'];
        }

        if (!empty($cur)) {
            $lines[] = $finalizeLine($cur, true);
        }

        return $lines;
    }

    /**
     * Draw a single wrapped rich line into the active PDF page stream.
     */
    protected function drawRichLine(array $line, float $colX, float $topY, float $colW, array $model, bool $isFirstLine, bool $isLastLine): void
    {
        $indent = $model['indLeft'] + ($isFirstLine ? $model['firstLine'] : 0.0);
        $availW = max(20.0, $colW - $indent - $model['indRight']);
        $align = $model['align'] ?? 'left';

        $startX = $colX + $indent;
        $extraPerSpace = 0.0;

        if ($align === 'center') {
            $startX += max(0.0, ($availW - $line['width']) / 2.0);
        } elseif ($align === 'right') {
            $startX += max(0.0, $availW - $line['width']);
        } elseif ($align === 'justify' && !$isLastLine && $line['spaceCount'] > 0 && $line['width'] < $availW) {
            $gap = $availW - $line['width'];
            // Cap justification stretch so sparse lines never look awkwardly gapped
            $extraPerSpace = min(6.0, $gap / $line['spaceCount']);
        }

        $cursorX = $startX;
        $baseY = $topY - $line['ascent'];

        foreach ($line['items'] as $it) {
            if ($it['type'] === 'image') {
                $imgY = $topY - $it['height'];
                $this->append(sprintf("q %.2F 0 0 %.2F %.2F %.2F cm /%s Do Q", $it['width'], $it['height'], $cursorX, $imgY, $it['key']));
                $cursorX += $it['width'];
                continue;
            }

            if ($it['type'] === 'space') {
                $cursorX += $it['width'] + $extraPerSpace;
                continue;
            }

            $yOffset = 0.0;
            if ($it['vertAlign'] === 'superscript') {
                $yOffset = ($it['baseSize'] ?? $it['size']) * 0.34;
            } elseif ($it['vertAlign'] === 'subscript') {
                $yOffset = -($it['baseSize'] ?? $it['size']) * 0.18;
            }

            // Inline badge / shaded background (e.g. teal RESEARCH ARTICLE pill)
            if (!empty($it['bg'])) {
                [$br, $bg, $bb] = $this->hexToRgb($it['bg']);
                $boxH = $it['size'] + 4.0;
                $boxY = $baseY - 2.2;
                $this->append(sprintf("%.3F %.3F %.3F rg %.2F %.2F %.2F %.2F re f", $br, $bg, $bb, $cursorX, $boxY, $it['width'], $boxH));
            }

            [$r, $g, $b] = $this->hexToRgb($it['color']);
            $textX = !empty($it['isBadge']) ? ($cursorX + 2.0) : $cursorX;
            $this->append($this->pdfTextCmd($textX, $baseY + $yOffset, $it['text'], $it['font'], $it['size'], $r, $g, $b));

            if (!empty($it['underline'])) {
                $this->append(sprintf("0.50 w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S", $r, $g, $b, $cursorX, $baseY - 1.2, $cursorX + $it['width'], $baseY - 1.2));
            }

            $cursorX += $it['width'];
        }
    }

    // =========================================================================
    // TABLE PARSING & RENDERING (ABSTRACT BOX, DATA TABLES, HEADER TABLES)
    // =========================================================================

    protected function renderTableNode(\DOMElement $tbl, array $rels): void
    {
        $colW = $this->activeColumnWidth();
        $declaredW = $this->measureDeclaredTableWidth($tbl);

        // Wide table inside 2-column section -> span full page width cleanly
        $spanFull = ($this->numCols >= 2 && $declaredW > ($colW * 1.15));
        $targetW = $spanFull ? $this->contentWidth() : $colW;

        $model = $this->buildTableModel($tbl, $rels, $targetW, false);
        if (!$model || empty($model['rows'])) {
            return;
        }

        if ($spanFull) {
            $firstNeed = min($model['totalHeight'], ($model['rows'][0]['height'] ?? 20.0) + 20.0);
            $this->prepareFullWidthBlock($firstNeed);
            $headerRow = !empty($model['rows'][0]['isHeader']) ? $model['rows'][0] : null;
            foreach ($model['rows'] as $rIdx => $row) {
                if (($this->currentY - $row['height']) < $this->mBottom) {
                    $this->addPage();
                    if ($headerRow && $rIdx > 0) {
                        $this->currentY = $this->drawTableRowAt($headerRow, $model['tableX'] + $this->mLeft, $this->currentY);
                    }
                }
                $this->currentY = $this->drawTableRowAt($row, $model['tableX'] + $this->mLeft, $this->currentY);
            }
            $this->currentY -= 6.0;
            $this->currentCol = 0;
            $this->colTopY = $this->currentY;
            $this->minColY = $this->currentY;
            return;
        }

        // Standard table in active column (e.g. Abstract Box in Section 1 or column Data Table in Section 2)
        $this->currentY -= 4.0;
        $headerRow = !empty($model['rows'][0]['isHeader']) ? $model['rows'][0] : null;

        // If entire table fits in a fresh column/page and doesn't fit here, move together cleanly when small
        if ($model['totalHeight'] <= ($this->pageH - $this->mTop - $this->mBottom) * 0.45 && ($this->currentY - $model['totalHeight']) < $this->mBottom) {
            $this->ensureSpace($model['totalHeight']);
        }

        foreach ($model['rows'] as $rIdx => $row) {
            if (($this->currentY - $row['height']) < $this->mBottom) {
                $this->ensureSpace($row['height']);
                if ($headerRow && $rIdx > 0) {
                    $x = $this->activeColumnLeftX() + $model['tableX'];
                    $this->currentY = $this->drawTableRowAt($headerRow, $x, $this->currentY);
                }
            }
            $x = $this->activeColumnLeftX() + $model['tableX'];
            $this->currentY = $this->drawTableRowAt($row, $x, $this->currentY);
            $this->recordMinY();
        }

        $this->currentY -= 6.0;
        $this->recordMinY();
    }

    protected function measureDeclaredTableWidth(\DOMElement $tbl): float
    {
        $tblW = 0.0;
        $gridW = 0.0;
        foreach ($tbl->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            if ($c->localName === 'tblPr') {
                foreach ($c->childNodes as $tp) {
                    if ($tp->nodeType === XML_ELEMENT_NODE && $tp->localName === 'tblW') {
                        $w = (int) $tp->getAttributeNS(self::W_NS, 'w');
                        $t = $tp->getAttributeNS(self::W_NS, 'type');
                        if ($t === 'dxa' && $w > 0) $tblW = $w / 20.0;
                    }
                }
            } elseif ($c->localName === 'tblGrid') {
                foreach ($c->childNodes as $gc) {
                    if ($gc->nodeType === XML_ELEMENT_NODE && $gc->localName === 'gridCol') {
                        $gridW += ((int) $gc->getAttributeNS(self::W_NS, 'w')) / 20.0;
                    }
                }
            }
        }
        return max($tblW, $gridW);
    }

    protected function buildTableModel(\DOMElement $tbl, array $rels, float $containerW, bool $isHeaderFooter = false): ?array
    {
        $tblBorders = [];
        $tblCellMar = ['top' => 3.0, 'right' => 4.5, 'bottom' => 3.0, 'left' => 4.5];
        $tblAlign = 'left';
        $gridCols = [];
        $trNodes = [];

        foreach ($tbl->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            if ($c->localName === 'tblPr') {
                foreach ($c->childNodes as $tp) {
                    if ($tp->nodeType !== XML_ELEMENT_NODE) continue;
                    if ($tp->localName === 'tblBorders') $tblBorders = $this->parseBorders($tp);
                    elseif ($tp->localName === 'tblCellMar') $tblCellMar = array_merge($tblCellMar, $this->parseMargins($tp));
                    elseif ($tp->localName === 'jc') $tblAlign = $tp->getAttributeNS(self::W_NS, 'val') ?: 'left';
                }
            } elseif ($c->localName === 'tblGrid') {
                foreach ($c->childNodes as $gc) {
                    if ($gc->nodeType === XML_ELEMENT_NODE && $gc->localName === 'gridCol') {
                        $gw = (int) $gc->getAttributeNS(self::W_NS, 'w');
                        $gridCols[] = $gw > 0 ? ($gw / 20.0) : 0.0;
                    }
                }
            } elseif ($c->localName === 'tr') {
                $trNodes[] = $c;
            }
        }

        if (empty($trNodes)) {
            return null;
        }

        $declaredW = $this->measureDeclaredTableWidth($tbl);
        $renderTableW = ($declaredW > 0 && !$isHeaderFooter) ? min($declaredW, $containerW) : $containerW;
        if ($declaredW > 0 && $declaredW >= ($containerW * 0.85)) {
            $renderTableW = $containerW;
        }
        $tableX = ($tblAlign === 'center') ? max(0.0, ($containerW - $renderTableW) / 2.0) : 0.0;

        $totalRows = count($trNodes);
        $rows = [];
        $totalHeight = 0.0;

        foreach ($trNodes as $rIdx => $tr) {
            $isHeaderRow = false;
            $tcs = [];
            foreach ($tr->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE) continue;
                if ($c->localName === 'trPr') {
                    foreach ($c->childNodes as $tp) {
                        if ($tp->nodeType === XML_ELEMENT_NODE && $tp->localName === 'tblHeader') {
                            $isHeaderRow = true;
                        }
                    }
                } elseif ($c->localName === 'tc') {
                    $tcs[] = $c;
                }
            }

            if (empty($tcs)) continue;

            // Compute widths for cells in this row
            $numTcs = count($tcs);
            $colCursor = 0;
            $rawCellWidths = [];
            $tcPrs = [];
            foreach ($tcs as $idx => $tc) {
                $tcPr = $this->parseTcPr($tc);
                $tcPrs[$idx] = $tcPr;
                $span = max(1, (int) ($tcPr['gridSpan'] ?? 1));
                $w = (float) ($tcPr['width'] ?? 0.0);
                if ($w <= 0 && !empty($gridCols)) {
                    for ($k = $colCursor; $k < $colCursor + $span; $k++) {
                        $w += $gridCols[$k] ?? 0.0;
                    }
                }
                $rawCellWidths[$idx] = $w;
                $colCursor += $span;
            }

            $sumRaw = array_sum($rawCellWidths);
            $cells = [];
            $rowH = 10.0;
            $offsetX = 0.0;

            foreach ($tcs as $idx => $tc) {
                $tcPr = $tcPrs[$idx];
                $cellW = $sumRaw > 0
                    ? ($renderTableW * ($rawCellWidths[$idx] / $sumRaw))
                    : ($renderTableW / max(1, $numTcs));

                $mar = array_merge($tblCellMar, $tcPr['margins'] ?? []);
                $innerW = max(16.0, $cellW - $mar['left'] - $mar['right']);

                $cellModels = [];
                $cellContentH = 0.0;
                foreach ($tc->childNodes as $cc) {
                    if ($cc->nodeType !== XML_ELEMENT_NODE) continue;
                    if ($cc->localName === 'p') {
                        $pm = $this->buildParagraphModel($cc, $rels, $innerW, $isHeaderFooter);
                        if ($pm) {
                            $cellModels[] = $pm;
                            $cellContentH += $pm['totalHeight'];
                        }
                    }
                }

                $cellTotalH = $mar['top'] + $cellContentH + $mar['bottom'];
                if ($cellTotalH > $rowH) {
                    $rowH = $cellTotalH;
                }

                // Resolve effective cell borders (table defaults + cell overrides)
                $borders = [
                    'top' => $rIdx === 0 ? ($tblBorders['top'] ?? null) : ($tblBorders['insideH'] ?? null),
                    'bottom' => ($rIdx === $totalRows - 1) ? ($tblBorders['bottom'] ?? null) : ($tblBorders['insideH'] ?? null),
                    'left' => $idx === 0 ? ($tblBorders['left'] ?? null) : ($tblBorders['insideV'] ?? null),
                    'right' => ($idx === $numTcs - 1) ? ($tblBorders['right'] ?? null) : ($tblBorders['insideV'] ?? null),
                ];
                if (!empty($tcPr['borders'])) {
                    foreach ($tcPr['borders'] as $side => $b) {
                        $borders[$side] = $b;
                    }
                }

                $cells[] = [
                    'offsetX' => $offsetX,
                    'width' => $cellW,
                    'innerW' => $innerW,
                    'margins' => $mar,
                    'borders' => $borders,
                    'shd' => $tcPr['shd'] ?? null,
                    'vAlign' => $tcPr['vAlign'] ?? 'top',
                    'contentHeight' => $cellContentH,
                    'paragraphs' => $cellModels,
                ];
                $offsetX += $cellW;
            }

            $rows[] = [
                'isHeader' => $isHeaderRow,
                'height' => $rowH,
                'cells' => $cells,
            ];
            $totalHeight += $rowH;
        }

        return [
            'tableX' => $tableX,
            'tableW' => $renderTableW,
            'rows' => $rows,
            'totalHeight' => $totalHeight,
        ];
    }

    protected function drawTableModelAt(array $model, float $baseX, float $y): float
    {
        foreach ($model['rows'] as $row) {
            $y = $this->drawTableRowAt($row, $baseX + $model['tableX'], $y);
        }
        return $y;
    }

    protected function drawTableRowAt(array $row, float $rowX, float $topY): float
    {
        $rowH = $row['height'];
        $botY = $topY - $rowH;

        foreach ($row['cells'] as $cell) {
            $cx = $rowX + $cell['offsetX'];
            $cw = $cell['width'];
            $mar = $cell['margins'];

            // 1. Background fill
            if (!empty($cell['shd']) && strtoupper($cell['shd']) !== 'FFFFFF') {
                [$r, $g, $b] = $this->hexToRgb($cell['shd']);
                $this->append(sprintf("%.3F %.3F %.3F rg %.2F %.2F %.2F %.2F re f", $r, $g, $b, $cx, $botY, $cw, $rowH));
            }

            // 2. Vertical alignment offset
            $availInnerH = max(0.0, $rowH - $mar['top'] - $mar['bottom']);
            $extraV = max(0.0, $availInnerH - $cell['contentHeight']);
            $vOffset = match ($cell['vAlign']) {
                'middle' => $extraV / 2.0,
                'bottom' => $extraV,
                default => 0.0,
            };

            // 3. Draw cell paragraphs
            $py = $topY - $mar['top'] - $vOffset;
            foreach ($cell['paragraphs'] as $pm) {
                $py = $this->drawParagraphModelAt($pm, $cx + $mar['left'], $py, $cell['innerW']);
            }

            // 4. Cell borders
            $this->drawParagraphBorders($cell['borders'], $cx, $topY, $botY, $cw);
        }

        return $botY;
    }

    // =========================================================================
    // IMAGE EXTRACTION (JPEG NATIVE /DCTDecode & PNG VIA GD /FlateDecode)
    // =========================================================================

    protected function registerDrawingImage(\DOMElement $node, array $rels, float $maxW): ?array
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
            $imgData = $xpath->query('.//v:imagedata', $node)->item(0);
            if ($imgData instanceof \DOMElement) {
                $rId = $imgData->getAttributeNS(self::R_NS, 'id');
            }
        }

        if (!$rId || !isset($rels[$rId])) {
            return null;
        }

        $target = $this->normalizeWordTarget($rels[$rId]['target']);
        $imgKey = $this->embedMediaImage($target);
        if (!$imgKey) {
            return null;
        }

        $imgObj = $this->pdfImages[$imgKey];
        $wPt = 0.0;
        $hPt = 0.0;

        $extent = $xpath->query('.//wp:extent', $node)->item(0);
        if ($extent instanceof \DOMElement) {
            $cx = (float) $extent->getAttribute('cx');
            $cy = (float) $extent->getAttribute('cy');
            if ($cx > 0) $wPt = $cx / 12700.0;
            if ($cy > 0) $hPt = $cy / 12700.0;
        }

        if ($wPt <= 0 || $hPt <= 0) {
            $wPt = min($maxW, $imgObj['w'] * 0.75);
            $hPt = $wPt * ($imgObj['h'] / max(1, $imgObj['w']));
        }

        $naturalW = $wPt;
        if ($wPt > $maxW && $maxW > 10.0) {
            $scale = $maxW / $wPt;
            $wPt = $maxW;
            $hPt *= $scale;
        }

        // Also clamp image height to usable page height so tall figures never clip header/footer
        $maxPageH = max(100.0, ($this->pageH - $this->mTop - $this->mBottom) - 24.0);
        if ($hPt > $maxPageH) {
            $scale = $maxPageH / $hPt;
            $hPt = $maxPageH;
            $wPt *= $scale;
        }

        return [
            'key' => $imgKey,
            'w' => $wPt,
            'h' => $hPt,
            'naturalW' => $naturalW,
        ];
    }

    protected function embedMediaImage(string $zipPath): ?string
    {
        if (array_key_exists($zipPath, $this->mediaToImageKey)) {
            return $this->mediaToImageKey[$zipPath];
        }

        $bytes = $this->getZipEntry($zipPath);
        if (!$bytes) {
            $this->mediaToImageKey[$zipPath] = null;
            return null;
        }

        $ext = strtolower(pathinfo($zipPath, PATHINFO_EXTENSION));

        // 1. Native JPEG embedding (/DCTDecode) — works even without GD
        if (in_array($ext, ['jpg', 'jpeg'], true) || str_starts_with($bytes, "\xFF\xD8\xFF")) {
            $info = @getimagesizefromstring($bytes);
            if ($info && $info[0] > 0 && $info[1] > 0) {
                $this->imageSeq++;
                $key = 'Im' . $this->imageSeq;
                $cs = (($info['channels'] ?? 3) === 1) ? '/DeviceGray' : '/DeviceRGB';
                $this->pdfImages[$key] = [
                    'w' => $info[0],
                    'h' => $info[1],
                    'colorSpace' => $cs,
                    'filter' => '/DCTDecode',
                    'data' => $bytes,
                ];
                $this->mediaToImageKey[$zipPath] = $key;
                return $key;
            }
        }

        // 2. PNG / GIF / WebP via GD -> RGB stream compressed with /FlateDecode
        if (function_exists('imagecreatefromstring') && function_exists('gzcompress')) {
            $im = @imagecreatefromstring($bytes);
            if ($im) {
                $w = imagesx($im);
                $h = imagesy($im);

                // Downscale huge images (> 1200px) so PDF stays lightweight and fast on shared hosting
                if ($w > 1200) {
                    $newW = 1200;
                    $newH = (int) round($h * ($newW / $w));
                    $resized = imagecreatetruecolor($newW, $newH);
                    $white = imagecolorallocate($resized, 255, 255, 255);
                    imagefilledrectangle($resized, 0, 0, $newW, $newH, $white);
                    imagecopyresampled($resized, $im, 0, 0, 0, 0, $newW, $newH, $w, $h);
                    imagedestroy($im);
                    $im = $resized;
                    $w = $newW;
                    $h = $newH;
                }

                // Flatten alpha onto crisp white canvas and convert to JPEG bytes (fast + small!)
                $canvas = imagecreatetruecolor($w, $h);
                $white = imagecolorallocate($canvas, 255, 255, 255);
                imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
                imagecopy($canvas, $im, 0, 0, 0, 0, $w, $h);
                imagedestroy($im);

                ob_start();
                imagejpeg($canvas, null, 90);
                $jpgBytes = ob_get_clean();
                imagedestroy($canvas);

                if ($jpgBytes && str_starts_with($jpgBytes, "\xFF\xD8\xFF")) {
                    $this->imageSeq++;
                    $key = 'Im' . $this->imageSeq;
                    $this->pdfImages[$key] = [
                        'w' => $w,
                        'h' => $h,
                        'colorSpace' => '/DeviceRGB',
                        'filter' => '/DCTDecode',
                        'data' => $jpgBytes,
                    ];
                    $this->mediaToImageKey[$zipPath] = $key;
                    return $key;
                }
            }
        }

        $this->mediaToImageKey[$zipPath] = null;
        return null;
    }

    // =========================================================================
    // OOXML PARSERS (SECTIONS, STYLES, NUMBERING, BORDERS, RELS)
    // =========================================================================

    protected function splitIntoSections(\DOMNode $body): array
    {
        $sections = [];
        $currentNodes = [];

        foreach ($body->childNodes as $child) {
            if ($child->nodeType !== XML_ELEMENT_NODE) continue;
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
            $sections[] = ['nodes' => $currentNodes, 'sectPr' => []];
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
                if ($c->localName === 'drawing' || $c->localName === 'pict') return true;
                if ($this->nodeHasDrawing($c)) return true;
            }
        }
        return false;
    }

    protected function parseSectPr(\DOMElement $sectPr): array
    {
        $info = [
            'pageW' => 595.28, 'pageH' => 841.89,
            'mTop' => 65.0, 'mRight' => 54.0, 'mBottom' => 55.0, 'mLeft' => 54.0,
            'mHeader' => 22.0, 'mFooter' => 22.0,
            'cols' => 1, 'colSpace' => 21.25, 'type' => 'nextPage',
            'headerRef' => null, 'footerRef' => null,
        ];

        foreach ($sectPr->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            switch ($c->localName) {
                case 'pgSz':
                    $w = (int) $c->getAttributeNS(self::W_NS, 'w');
                    $h = (int) $c->getAttributeNS(self::W_NS, 'h');
                    if ($w > 0) $info['pageW'] = $w / 20.0;
                    if ($h > 0) $info['pageH'] = $h / 20.0;
                    break;
                case 'pgMar':
                    foreach (['top' => 'mTop', 'right' => 'mRight', 'bottom' => 'mBottom', 'left' => 'mLeft', 'header' => 'mHeader', 'footer' => 'mFooter'] as $a => $k) {
                        $v = $c->getAttributeNS(self::W_NS, $a);
                        if ($v !== '') $info[$k] = ((int) $v) / 20.0;
                    }
                    break;
                case 'cols':
                    $num = (int) $c->getAttributeNS(self::W_NS, 'num');
                    $sp = (int) $c->getAttributeNS(self::W_NS, 'space');
                    if ($num > 0) $info['cols'] = $num;
                    if ($sp > 0) $info['colSpace'] = $sp / 20.0;
                    break;
                case 'type':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    if ($v !== '') $info['type'] = $v;
                    break;
                case 'headerReference':
                    $t = $c->getAttributeNS(self::W_NS, 'type') ?: 'default';
                    $rid = $c->getAttributeNS(self::R_NS, 'id');
                    if ($rid && ($t === 'default' || $info['headerRef'] === null)) $info['headerRef'] = $rid;
                    break;
                case 'footerReference':
                    $t = $c->getAttributeNS(self::W_NS, 'type') ?: 'default';
                    $rid = $c->getAttributeNS(self::R_NS, 'id');
                    if ($rid && ($t === 'default' || $info['footerRef'] === null)) $info['footerRef'] = $rid;
                    break;
            }
        }
        return $info;
    }

    protected function parseStyles(): void
    {
        $xml = $this->getZipEntry('word/styles.xml');
        if (!$xml) return;

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) return;

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', self::W_NS);

        $defRPr = $xpath->query('//w:docDefaults/w:rPrDefault/w:rPr')->item(0);
        if ($defRPr instanceof \DOMElement) $this->docDefaultRPr = $this->parseRPr($defRPr);

        $defPPr = $xpath->query('//w:docDefaults/w:pPrDefault/w:pPr')->item(0);
        if ($defPPr instanceof \DOMElement) $this->docDefaultPPr = $this->parsePPr($defPPr);

        foreach ($xpath->query('//w:style') as $sn) {
            if (!($sn instanceof \DOMElement)) continue;
            $id = $sn->getAttributeNS(self::W_NS, 'styleId');
            if ($id === '') continue;

            $basedOn = null; $pPr = []; $rPr = [];
            foreach ($sn->childNodes as $c) {
                if ($c->nodeType !== XML_ELEMENT_NODE) continue;
                if ($c->localName === 'basedOn') $basedOn = $c->getAttributeNS(self::W_NS, 'val');
                elseif ($c->localName === 'pPr') $pPr = $this->parsePPr($c);
                elseif ($c->localName === 'rPr') $rPr = $this->parseRPr($c);
            }
            $this->styles[$id] = ['basedOn' => $basedOn, 'pPr' => $pPr, 'rPr' => $rPr];
        }
    }

    protected function resolveStyle(?string $styleId, int $depth = 0): array
    {
        if (empty($styleId) || !isset($this->styles[$styleId]) || $depth > 8) {
            return ['pPr' => [], 'rPr' => []];
        }
        $s = $this->styles[$styleId];
        $parent = !empty($s['basedOn']) ? $this->resolveStyle($s['basedOn'], $depth + 1) : ['pPr' => [], 'rPr' => []];
        return [
            'pPr' => array_merge($parent['pPr'], $s['pPr']),
            'rPr' => array_merge($parent['rPr'], $s['rPr']),
        ];
    }

    protected function parseNumbering(): void
    {
        $xml = $this->getZipEntry('word/numbering.xml');
        if (!$xml) return;

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        if (!@$dom->loadXML($xml)) return;

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
                $fmt = 'bullet'; $txt = '•'; $start = 1; $indLeft = 18.0; $hanging = 14.0;
                foreach ($c->childNodes as $lc) {
                    if ($lc->nodeType !== XML_ELEMENT_NODE) continue;
                    if ($lc->localName === 'numFmt') $fmt = $lc->getAttributeNS(self::W_NS, 'val');
                    elseif ($lc->localName === 'lvlText') $txt = $lc->getAttributeNS(self::W_NS, 'val');
                    elseif ($lc->localName === 'start') $start = (int) $lc->getAttributeNS(self::W_NS, 'val');
                    elseif ($lc->localName === 'pPr') {
                        $lp = $this->parsePPr($lc);
                        if (!empty($lp['indLeft'])) $indLeft = (float) $lp['indLeft'];
                        if (!empty($lp['hanging'])) $hanging = (float) $lp['hanging'];
                    }
                }
                $levels[$ilvl] = ['fmt' => $fmt, 'text' => $txt, 'start' => $start, 'indentLeft' => $indLeft, 'hanging' => $hanging];
            }
            $abstracts[$absId] = $levels;
        }

        foreach ($xpath->query('//w:num') as $num) {
            if (!($num instanceof \DOMElement)) continue;
            $numId = $num->getAttributeNS(self::W_NS, 'numId');
            foreach ($num->childNodes as $c) {
                if ($c->nodeType === XML_ELEMENT_NODE && $c->localName === 'abstractNumId') {
                    $absId = $c->getAttributeNS(self::W_NS, 'val');
                    if (isset($abstracts[$absId])) $this->numbering[$numId] = $abstracts[$absId];
                }
            }
        }
    }

    protected function getListMarker(string $numId, int $ilvl): ?array
    {
        if (!isset($this->numbering[$numId])) return null;
        $lvl = $this->numbering[$numId][$ilvl] ?? ($this->numbering[$numId][0] ?? null);
        if (!$lvl) return null;

        $key = $numId . ':' . $ilvl;
        $this->listCounters[$key] = isset($this->listCounters[$key]) ? ($this->listCounters[$key] + 1) : max(1, (int) $lvl['start']);
        $n = $this->listCounters[$key];

        if ($lvl['fmt'] === 'bullet') {
            $marker = '•';
        } else {
            $marker = preg_replace('/%\d+/', (string) $n, $lvl['text'] ?: '%1.');
        }

        return [
            'marker' => $marker,
            'indentLeft' => (float) $lvl['indentLeft'],
            'hanging' => (float) $lvl['hanging'],
        ];
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
                        if ($ratio >= 0.8 && $ratio <= 3.0) $out['lineRatio'] = max(1.14, $ratio * 1.12);
                    }
                    break;
                case 'ind':
                    $left = $c->getAttributeNS(self::W_NS, 'left');
                    $right = $c->getAttributeNS(self::W_NS, 'right');
                    $first = $c->getAttributeNS(self::W_NS, 'firstLine');
                    $hang = $c->getAttributeNS(self::W_NS, 'hanging');
                    if ($left !== '') $out['indLeft'] = ((int) $left) / 20.0;
                    if ($right !== '') $out['indRight'] = ((int) $right) / 20.0;
                    if ($first !== '') $out['firstLine'] = ((int) $first) / 20.0;
                    if ($hang !== '') $out['hanging'] = ((int) $hang) / 20.0;
                    break;
                case 'pBdr':
                    $out['borders'] = $this->parseBorders($c);
                    break;
                case 'shd':
                    $fill = $c->getAttributeNS(self::W_NS, 'fill');
                    if ($fill !== '' && strtolower($fill) !== 'auto' && strtoupper($fill) !== 'FFFFFF') $out['shd'] = $fill;
                    break;
                case 'keepNext':
                    $out['keepNext'] = true;
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
                case 'rStyle': $out['rStyle'] = $c->getAttributeNS(self::W_NS, 'val'); break;
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
                case 'caps':
                    $v = $c->getAttributeNS(self::W_NS, 'val');
                    $out['caps'] = ($v !== '0' && strtolower($v) !== 'false');
                    break;
                case 'vertAlign': $out['vertAlign'] = $c->getAttributeNS(self::W_NS, 'val'); break;
                case 'shd':
                    $fill = $c->getAttributeNS(self::W_NS, 'fill');
                    if ($fill !== '' && strtolower($fill) !== 'auto' && strtoupper($fill) !== 'FFFFFF') $out['shd'] = $fill;
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
                    case 'gridSpan': $out['gridSpan'] = (int) $tp->getAttributeNS(self::W_NS, 'val'); break;
                    case 'tcBorders': $out['borders'] = $this->parseBorders($tp); break;
                    case 'shd':
                        $fill = $tp->getAttributeNS(self::W_NS, 'fill');
                        if ($fill !== '' && strtolower($fill) !== 'auto') $out['shd'] = $fill;
                        break;
                    case 'tcMar': $out['margins'] = $this->parseMargins($tp); break;
                    case 'vAlign':
                        $v = $tp->getAttributeNS(self::W_NS, 'val');
                        $out['vAlign'] = match ($v) { 'center' => 'middle', 'bottom' => 'bottom', default => 'top' };
                        break;
                }
            }
        }
        return $out;
    }

    protected function parseBorders(\DOMElement $node): array
    {
        $out = [];
        foreach ($node->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            $side = $c->localName;
            if (!in_array($side, ['top', 'bottom', 'left', 'right', 'insideH', 'insideV'], true)) continue;
            $val = $c->getAttributeNS(self::W_NS, 'val');
            if ($val === '' || $val === 'none' || $val === 'nil') {
                $out[$side] = ['style' => 'none', 'width' => 0.0, 'color' => 'FFFFFF'];
                continue;
            }
            $sz = (int) $c->getAttributeNS(self::W_NS, 'sz');
            $col = $c->getAttributeNS(self::W_NS, 'color');
            if ($col === '' || strtolower($col) === 'auto') $col = '888888';
            $out[$side] = [
                'style' => $val,
                'width' => max(0.5, $sz / 8.0),
                'color' => $col,
            ];
        }
        return $out;
    }

    protected function parseMargins(\DOMElement $node): array
    {
        $out = [];
        foreach ($node->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            if (in_array($c->localName, ['top', 'bottom', 'left', 'right'], true)) {
                $w = $c->getAttributeNS(self::W_NS, 'w');
                if ($w !== '') $out[$c->localName] = ((int) $w) / 20.0;
            }
        }
        return $out;
    }

    protected function parseRels(string $relsPath): array
    {
        $xml = $this->getZipEntry($relsPath);
        if (!$xml) return [];
        $dom = new \DOMDocument();
        if (!@$dom->loadXML($xml)) return [];

        $rels = [];
        foreach ($dom->getElementsByTagName('Relationship') as $rel) {
            $id = $rel->getAttribute('Id');
            $target = $rel->getAttribute('Target');
            if ($id !== '' && $target !== '') {
                $rels[$id] = ['target' => $target];
            }
        }
        return $rels;
    }

    protected function normalizeWordTarget(string $target): string
    {
        $target = ltrim($target, '/');
        if (str_starts_with($target, '../')) return substr($target, 3);
        if (!str_starts_with($target, 'word/')) return 'word/' . $target;
        return $target;
    }

    // =========================================================================
    // PURE-PHP ZIP READER (NO ZipArchive REQUIRED!)
    // =========================================================================

    protected function openZip(?string $filePath, string $rawBytes): bool
    {
        if ($filePath && class_exists(\ZipArchive::class)) {
            $za = new \ZipArchive();
            if ($za->open($filePath) === true) {
                $this->zipArchive = $za;
                return true;
            }
        }

        if ($rawBytes !== '') {
            $this->rawZipData = $rawBytes;
            return true;
        }

        if ($filePath && file_exists($filePath)) {
            $data = @file_get_contents($filePath);
            if ($data !== false && str_starts_with($data, 'PK')) {
                $this->rawZipData = $data;
                return true;
            }
        }

        return false;
    }

    protected function closeZip(): void
    {
        if ($this->zipArchive) {
            @$this->zipArchive->close();
            $this->zipArchive = null;
        }
        $this->rawZipData = '';
        $this->zipEntryCache = [];
    }

    protected function getZipEntry(string $target): ?string
    {
        if (array_key_exists($target, $this->zipEntryCache)) {
            return $this->zipEntryCache[$target];
        }

        if ($this->zipArchive) {
            $res = $this->zipArchive->getFromName($target);
            $this->zipEntryCache[$target] = ($res !== false) ? $res : null;
            return $this->zipEntryCache[$target];
        }

        $res = $this->extractPureZipEntry($this->rawZipData, $target);
        $this->zipEntryCache[$target] = $res;
        return $res;
    }

    protected function extractPureZipEntry(string $zipData, string $targetFile): ?string
    {
        $len = strlen($zipData);
        if ($len < 22) return null;

        $eocdPos = strrpos($zipData, "PK\x05\x06");
        if ($eocdPos !== false && $eocdPos + 22 <= $len) {
            $eocd = @unpack('vdisk/vdiskStart/vtotalSec/vtotal/VcdSize/VcdOffset', substr($zipData, $eocdPos + 4, 18));
            if ($eocd && isset($eocd['cdOffset'], $eocd['cdSize'])) {
                $pos = $eocd['cdOffset'];
                $end = min($pos + $eocd['cdSize'], $len);
                while ($pos < $end && ($pos = strpos($zipData, "PK\x01\x02", $pos)) !== false) {
                    if ($pos + 46 > $len) break;
                    $cd = @unpack('vver/vverReq/vflags/vmethod/vtime/vdate/Vcrc/VcSize/VuSize/vnameLen/vextraLen/vcommLen/vdisk/vattr/VextAttr/Voffset', substr($zipData, $pos + 4, 42));
                    if (!$cd) break;

                    $filename = substr($zipData, $pos + 46, $cd['nameLen']);
                    if ($filename === $targetFile) {
                        $localPos = $cd['offset'];
                        if ($localPos + 30 <= $len) {
                            $lh = @unpack('vver/vflags/vmethod/vtime/vdate/Vcrc/VcSize/VuSize/vnameLen/vextraLen', substr($zipData, $localPos + 4, 26));
                            if ($lh) {
                                $dataStart = $localPos + 30 + $lh['nameLen'] + $lh['extraLen'];
                                $compressed = substr($zipData, $dataStart, $cd['cSize']);
                                if ($cd['method'] == 8 && function_exists('gzinflate')) {
                                    return @gzinflate($compressed) ?: null;
                                } elseif ($cd['method'] == 0) {
                                    return $compressed;
                                }
                            }
                        }
                    }
                    $pos += 46 + $cd['nameLen'] + $cd['extraLen'] + $cd['commLen'];
                }
            }
        }
        return null;
    }

    // =========================================================================
    // PDF 1.4 FONT METRICS, ENCODING & COMPILATION
    // =========================================================================

    protected function pickPdfFont(array $rPr): string
    {
        $f = strtolower((string) ($rPr['font'] ?? ''));
        $bold = !empty($rPr['bold']);
        $italic = !empty($rPr['italic']);

        $isSans = str_contains($f, 'arial') || str_contains($f, 'calibri') || str_contains($f, 'helvetica')
            || str_contains($f, 'segoe') || str_contains($f, 'poppins') || str_contains($f, 'sans');

        if ($isSans) {
            if ($bold && $italic) return 'F7'; // Helvetica-BoldOblique
            if ($bold) return 'F2';            // Helvetica-Bold
            if ($italic) return 'F3';          // Helvetica-Oblique
            return 'F1';                       // Helvetica
        }

        if ($bold && $italic) return 'F8';     // Times-BoldItalic
        if ($bold) return 'F5';                // Times-Bold
        if ($italic) return 'F6';              // Times-Italic
        return 'F4';                           // Times-Roman
    }

    /**
     * Exact Adobe Standard Type1 AFM character width tables (1000 units = 1 em) for ASCII 32-126.
     */
    protected static array $afmWidths = [
        // Helvetica / Helvetica-Oblique
        'helv' => [
            32=>278,33=>278,34=>355,35=>556,36=>556,37=>889,38=>667,39=>191,40=>333,41=>333,42=>389,43=>584,44=>278,45=>333,46=>278,47=>278,
            48=>556,49=>556,50=>556,51=>556,52=>556,53=>556,54=>556,55=>556,56=>556,57=>556,58=>278,59=>278,60=>584,61=>584,62=>584,63=>556,
            64=>1015,65=>667,66=>667,67=>722,68=>722,69=>667,70=>611,71=>778,72=>722,73=>278,74=>500,75=>667,76=>556,77=>833,78=>722,79=>778,
            80=>667,81=>778,82=>722,83=>667,84=>611,85=>722,86=>667,87=>944,88=>667,89=>667,90=>611,91=>278,92=>278,93=>278,94=>469,95=>556,
            96=>333,97=>556,98=>556,99=>500,100=>556,101=>556,102=>278,103=>556,104=>556,105=>222,106=>222,107=>500,108=>222,109=>833,110=>556,
            111=>556,112=>556,113=>556,114=>333,115=>500,116=>278,117=>556,118=>500,119=>722,120=>500,121=>500,122=>500,123=>334,124=>260,125=>334,126=>584,
            149=>350,150=>556,151=>1000,145=>222,146=>222,147=>333,148=>333,169=>737,177=>549,
        ],
        // Helvetica-Bold / Helvetica-BoldOblique
        'helvb' => [
            32=>278,33=>333,34=>474,35=>556,36=>556,37=>889,38=>722,39=>238,40=>333,41=>333,42=>389,43=>584,44=>278,45=>333,46=>278,47=>278,
            48=>556,49=>556,50=>556,51=>556,52=>556,53=>556,54=>556,55=>556,56=>556,57=>556,58=>333,59=>333,60=>584,61=>584,62=>584,63=>611,
            64=>975,65=>722,66=>722,67=>722,68=>722,69=>667,70=>611,71=>778,72=>722,73=>278,74=>556,75=>722,76=>611,77=>833,78=>722,79=>778,
            80=>667,81=>778,82=>722,83=>667,84=>611,85=>722,86=>667,87=>944,88=>667,89=>667,90=>611,91=>333,92=>278,93=>333,94=>584,95=>556,
            96=>333,97=>556,98=>611,99=>556,100=>611,101=>556,102=>333,103=>611,104=>611,105=>278,106=>278,107=>556,108=>278,109=>889,110=>611,
            111=>611,112=>611,113=>611,114=>389,115=>556,116=>333,117=>611,118=>556,119=>778,120=>556,121=>556,122=>500,123=>389,124=>280,125=>389,126=>584,
            149=>350,150=>556,151=>1000,145=>278,146=>278,147=>500,148=>500,169=>737,177=>549,
        ],
        // Times-Roman
        'times' => [
            32=>250,33=>333,34=>408,35=>500,36=>500,37=>833,38=>778,39=>180,40=>333,41=>333,42=>500,43=>564,44=>250,45=>333,46=>250,47=>278,
            48=>500,49=>500,50=>500,51=>500,52=>500,53=>500,54=>500,55=>500,56=>500,57=>500,58=>278,59=>278,60=>564,61=>564,62=>564,63=>444,
            64=>921,65=>722,66=>667,67=>667,68=>722,69=>611,70=>556,71=>722,72=>722,73=>333,74=>389,75=>722,76=>611,77=>889,78=>722,79=>722,
            80=>556,81=>722,82=>667,83=>556,84=>611,85=>722,86=>722,87=>944,88=>722,89=>722,90=>611,91=>333,92=>278,93=>333,94=>469,95=>500,
            96=>333,97=>444,98=>500,99=>444,100=>500,101=>444,102=>333,103=>500,104=>500,105=>278,106=>278,107=>500,108=>278,109=>778,110=>500,
            111=>500,112=>500,113=>500,114=>333,115=>389,116=>278,117=>500,118=>500,119=>722,120=>500,121=>500,122=>444,123=>480,124=>200,125=>480,126=>541,
            149=>350,150=>500,151=>1000,145=>333,146=>333,147=>444,148=>444,169=>760,177=>549,
        ],
        // Times-Bold
        'timesb' => [
            32=>250,33=>333,34=>555,35=>500,36=>500,37=>1000,38=>833,39=>278,40=>333,41=>333,42=>500,43=>570,44=>250,45=>333,46=>250,47=>278,
            48=>500,49=>500,50=>500,51=>500,52=>500,53=>500,54=>500,55=>500,56=>500,57=>500,58=>333,59=>333,60=>570,61=>570,62=>570,63=>500,
            64=>930,65=>722,66=>667,67=>722,68=>722,69=>667,70=>611,71=>778,72=>778,73=>389,74=>500,75=>778,76=>667,77=>944,78=>722,79=>778,
            80=>611,81=>778,82=>722,83=>556,84=>667,85=>722,86=>722,87=>1000,88=>722,89=>722,90=>667,91=>333,92=>278,93=>333,94=>581,95=>500,
            96=>333,97=>500,98=>556,99=>444,100=>556,101=>444,102=>333,103=>500,104=>556,105=>278,106=>333,107=>556,108=>278,109=>833,110=>556,
            111=>500,112=>556,113=>556,114=>444,115=>389,116=>333,117=>556,118=>500,119=>722,120=>500,121=>500,122=>444,123=>394,124=>220,125=>394,126=>520,
            149=>350,150=>500,151=>1000,145=>333,146=>333,147=>500,148=>500,169=>747,177=>549,
        ],
        // Times-Italic
        'timesi' => [
            32=>250,33=>333,34=>420,35=>500,36=>500,37=>833,38=>778,39=>214,40=>333,41=>333,42=>500,43=>675,44=>250,45=>333,46=>250,47=>278,
            48=>500,49=>500,50=>500,51=>500,52=>500,53=>500,54=>500,55=>500,56=>500,57=>500,58=>333,59=>333,60=>675,61=>675,62=>675,63=>500,
            64=>920,65=>611,66=>611,67=>667,68=>722,69=>611,70=>611,71=>722,72=>722,73=>333,74=>444,75=>667,76=>556,77=>833,78=>667,79=>722,
            80=>611,81=>722,82=>611,83=>500,84=>556,85=>722,86=>611,87=>833,88=>611,89=>556,90=>556,91=>389,92=>278,93=>389,94=>422,95=>500,
            96=>333,97=>500,98=>500,99=>444,100=>500,101=>444,102=>278,103=>500,104=>500,105=>278,106=>278,107=>444,108=>278,109=>722,110=>500,
            111=>500,112=>500,113=>500,114=>389,115=>389,116=>278,117=>500,118=>444,119=>667,120=>444,121=>444,122=>389,123=>400,124=>275,125=>400,126=>541,
            149=>350,150=>500,151=>889,145=>333,146=>333,147=>556,148=>556,169=>760,177=>675,
        ],
    ];

    /**
     * Measure exact proportional string width in points using Adobe Standard Type1 AFM widths.
     */
    protected function measureString(string $text, string $font, float $size): float
    {
        $clean = $this->toWinAnsi($text);
        $len = strlen($clean);
        if ($len === 0) return 0.0;

        $tableKey = match ($font) {
            'F1', 'F3' => 'helv',
            'F2', 'F7' => 'helvb',
            'F5', 'F8' => 'timesb',
            'F6' => 'timesi',
            default => 'times',
        };
        $tbl = self::$afmWidths[$tableKey];

        $units = 0.0;
        for ($i = 0; $i < $len; $i++) {
            $ord = ord($clean[$i]);
            $units += $tbl[$ord] ?? 500;
        }

        return ($units / 1000.0) * $size;
    }

    protected function toWinAnsi(string $text): string
    {
        // Map common Unicode punctuation cleanly before iconv
        $text = str_replace(
            ['●', '○', '■', '▪', '▫', '−', '×', '≤', '≥', '≠', '→', '←', '≈', '∞'],
            ['•', 'o', '•', '•', '-', '-', 'x', '<=', '>=', '!=', '->', '<-', '~=', 'inf'],
            $text
        );
        $clean = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $text);
        if ($clean === false) {
            $clean = preg_replace('/[^\x20-\x7E]/', ' ', $text);
        }
        return (string) $clean;
    }

    protected function pdfEscape(string $text): string
    {
        $win = $this->toWinAnsi($text);
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $win);
    }

    protected function pdfTextCmd(float $x, float $y, string $text, string $font, float $size, float $r, float $g, float $b): string
    {
        return sprintf(
            "BT /%s %.2F Tf %.3F %.3F %.3F rg %.2F %.2F Td (%s) Tj ET\n",
            $font,
            $size,
            $r,
            $g,
            $b,
            $x,
            $y,
            $this->pdfEscape($text)
        );
    }

    protected function hexToRgb(?string $hex): array
    {
        $h = ltrim(trim((string) $hex), '#');
        if (strlen($h) === 3) {
            $h = $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2];
        }
        if (strlen($h) !== 6 || !ctype_xdigit($h)) {
            return [0.1, 0.1, 0.1];
        }
        return [
            hexdec(substr($h, 0, 2)) / 255.0,
            hexdec(substr($h, 2, 2)) / 255.0,
            hexdec(substr($h, 4, 2)) / 255.0,
        ];
    }

    /**
     * Compile all pages, standard Type1 fonts, and embedded JPEG XObjects into a valid PDF 1.4 binary.
     */
    protected function compilePdf(): string
    {
        $out = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $objects = [];

        $fonts = [
            'F1' => 'Helvetica',
            'F2' => 'Helvetica-Bold',
            'F3' => 'Helvetica-Oblique',
            'F4' => 'Times-Roman',
            'F5' => 'Times-Bold',
            'F6' => 'Times-Italic',
            'F7' => 'Helvetica-BoldOblique',
            'F8' => 'Times-BoldItalic',
        ];

        $nextId = 3;
        $fontRes = '';
        foreach ($fonts as $alias => $base) {
            $id = $nextId++;
            $objects[$id] = sprintf("<< /Type /Font /Subtype /Type1 /BaseFont /%s /Encoding /WinAnsiEncoding >>", $base);
            $fontRes .= sprintf("/%s %d 0 R ", $alias, $id);
        }

        // Image XObjects
        $xObjRes = '';
        foreach ($this->pdfImages as $key => $img) {
            $id = $nextId++;
            $objects[$id] = sprintf(
                "<< /Type /XObject /Subtype /Image /Width %d /Height %d /ColorSpace %s /BitsPerComponent 8 /Filter %s /Length %d >>\nstream\n%s\nendstream",
                $img['w'],
                $img['h'],
                $img['colorSpace'],
                $img['filter'],
                strlen($img['data']),
                $img['data']
            );
            $xObjRes .= sprintf("/%s %d 0 R ", $key, $id);
        }

        $pageObjIds = [];
        $contentObjIds = [];
        for ($p = 1; $p <= $this->pageCount; $p++) {
            $pageObjIds[$p] = $nextId++;
            $contentObjIds[$p] = $nextId++;
        }

        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";
        $kids = implode(' 0 R ', array_values($pageObjIds)) . ' 0 R';
        $objects[2] = sprintf("<< /Type /Pages /Kids [%s] /Count %d >>", $kids, $this->pageCount);

        $xObjDict = $xObjRes !== '' ? sprintf("/XObject << %s >>", $xObjRes) : '';

        for ($p = 1; $p <= $this->pageCount; $p++) {
            $stream = $this->pages[$p] . $this->buildFooterStream($p, $this->pageCount);
            $compressed = function_exists('gzcompress') ? @gzcompress($stream, 9) : false;

            if ($compressed !== false && strlen($compressed) < strlen($stream)) {
                $objects[$contentObjIds[$p]] = sprintf("<< /Filter /FlateDecode /Length %d >>\nstream\n%s\nendstream", strlen($compressed), $compressed);
            } else {
                $objects[$contentObjIds[$p]] = sprintf("<< /Length %d >>\nstream\n%s\nendstream", strlen($stream), $stream);
            }

            $objects[$pageObjIds[$p]] = sprintf(
                "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2F %.2F] /Contents %d 0 R /Resources << /Font << %s >> %s >> >>",
                $this->pageW,
                $this->pageH,
                $contentObjIds[$p],
                $fontRes,
                $xObjDict
            );
        }

        $offsets = [];
        $pos = strlen($out);
        ksort($objects);

        foreach ($objects as $id => $body) {
            $offsets[$id] = $pos;
            $chunk = sprintf("%d 0 obj\n%s\nendobj\n", $id, $body);
            $out .= $chunk;
            $pos += strlen($chunk);
        }

        $maxId = max(array_keys($objects));
        $out .= sprintf("xref\n0 %d\n", $maxId + 1);
        $out .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }
        $out .= sprintf("trailer\n<< /Size %d /Root 1 0 R >>\nstartxref\n%d\n%%%%EOF", $maxId + 1, $pos);

        return $out;
    }
}
