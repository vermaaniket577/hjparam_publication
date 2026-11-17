<?php

namespace App\Services;

class AcademicPdfService
{
    protected array $pages = [];
    protected array $offsets = [];
    protected string $buffer = '';
    protected int $pageCount = 0;
    protected float $pageWidth = 595.28; // A4 pt
    protected float $pageHeight = 841.89; // A4 pt
    protected float $marginLeft = 42.0;
    protected float $marginRight = 42.0;
    protected float $marginTop = 45.0;
    protected float $marginBottom = 45.0;
    protected float $currentY = 0.0;
    protected string $currentFont = 'F1';
    protected float $currentFontSize = 10.0;

    public function generate(array $data): string
    {
        $this->pages = [];
        $this->offsets = [];
        $this->buffer = '';
        $this->pageCount = 0;

        $this->addPage($data);

        // Header block
        $this->renderHeader($data);

        // Article Title
        $this->renderTitle($data['title'] ?? 'Research Article');

        // Authors & Affiliation
        $this->renderAuthors($data);

        // Metadata box (Dates & DOI)
        $this->renderMetadata($data);

        // Abstract & Keywords
        $this->renderAbstract($data);

        // Body Sections
        $this->renderBodySections($data);

        // Compile PDF objects
        return $this->compilePdf($data);
    }

    protected function addPage(array $data): void
    {
        $this->pageCount++;
        $this->pages[$this->pageCount] = '';
        $this->currentY = $this->pageHeight - $this->marginTop;
    }

    protected function checkPageBreak(float $neededHeight, array $data): void
    {
        if (($this->currentY - $neededHeight) < $this->marginBottom) {
            $this->addPage($data);
            $this->renderRunningHeader($data);
        }
    }

    protected function escape(string $text): string
    {
        // Transliterate UTF-8 to Windows-1252 / ISO-8859-1 for standard Type 1 fonts
        $clean = @iconv('UTF-8', 'windows-1252//TRANSLIT', $text);
        if ($clean === false) {
            $clean = utf8_decode($text);
        }
        $clean = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $clean);
        return $clean;
    }

    protected function appendToPage(string $content): void
    {
        $this->pages[$this->pageCount] .= $content . "\n";
    }

    protected function drawRect(float $x, float $y, float $w, float $h, float $r, float $g, float $b, bool $fill = true): void
    {
        $op = $fill ? 'f' : 'S';
        $colorCmd = $fill ? sprintf('%.3F %.3F %.3F rg', $r, $g, $b) : sprintf('%.3F %.3F %.3F RG', $r, $g, $b);
        $this->appendToPage(sprintf('%s %.2F %.2F %.2F %.2F re %s', $colorCmd, $x, $y, $w, $h, $op));
    }

    protected function drawLine(float $x1, float $y1, float $x2, float $y2, float $r = 0.8, float $g = 0.8, float $b = 0.8, float $w = 0.75): void
    {
        $this->appendToPage(sprintf('%.2F w %.3F %.3F %.3F RG %.2F %.2F m %.2F %.2F l S', $w, $r, $g, $b, $x1, $y1, $x2, $y2));
    }

    protected function drawText(float $x, float $y, string $text, string $font = 'F1', float $size = 10.0, float $r = 0.1, float $g = 0.1, float $b = 0.1): void
    {
        $escaped = $this->escape($text);
        $this->appendToPage(sprintf('BT /%s %.1F Tf %.3F %.3F %.3F rg %.2F %.2F Td (%s) Tj ET', $font, $size, $r, $g, $b, $x, $y, $escaped));
    }

    protected function wrapText(string $text, float $maxWidth, float $fontSize): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';
        $avgCharWidth = $fontSize * 0.50; // Approximation for Helvetica

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            $testWidth = strlen($testLine) * $avgCharWidth;

            if ($testWidth <= $maxWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }
                $currentLine = $word;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }

    protected function renderHeader(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $journalTitle = $data['journal_title'] ?? 'HJPARAM International Journal';
        $volNum = $data['volume'] ?? '1';
        $issueNum = $data['issue'] ?? '1';
        $issueMonth = $data['month'] ?? 'Regular';
        $issueYear = $data['year'] ?? date('Y');
        $issn = $data['issn'] ?? 'Applied (Online)';
        $doi = $data['doi'] ?? '';

        // Top decorative accent line
        $this->drawRect($this->marginLeft, $this->currentY - 2, $w, 3, 0.12, 0.23, 0.54, true);
        $this->currentY -= 16;

        // Journal Title
        $this->drawText($this->marginLeft, $this->currentY, $journalTitle, 'F2', 13.0, 0.12, 0.23, 0.54);

        // Right side badge
        $this->drawText($this->pageWidth - $this->marginRight - 130, $this->currentY + 2, 'HJPARAM ACADEMIC PRESS', 'F2', 8.0, 0.2, 0.2, 0.3);
        $this->drawText($this->pageWidth - $this->marginRight - 130, $this->currentY - 9, 'OPEN ACCESS  •  PEER-REVIEWED', 'F2', 7.0, 0.08, 0.55, 0.25);
        $this->currentY -= 14;

        // Sub-strip: Volume, Issue, ISSN
        $metaStrip = "Volume {$volNum}  |  Issue No. {$issueNum}  |  {$issueMonth} {$issueYear}  |  ISSN: {$issn}";
        $this->drawText($this->marginLeft, $this->currentY, $metaStrip, 'F1', 8.5, 0.35, 0.4, 0.48);
        $this->currentY -= 8;

        // Bottom divider rule
        $this->drawLine($this->marginLeft, $this->currentY, $this->pageWidth - $this->marginRight, $this->currentY, 0.8, 0.85, 0.9, 1.0);
        $this->currentY -= 18;
    }

    protected function renderRunningHeader(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $journalTitle = $data['journal_title'] ?? 'HJPARAM Publication';
        $volNum = $data['volume'] ?? '1';
        $issueNum = $data['issue'] ?? '1';
        $year = $data['year'] ?? date('Y');

        $headerText = "{$journalTitle}  •  Vol. {$volNum}, Issue {$issueNum} ({$year})";
        $this->drawText($this->marginLeft, $this->pageHeight - 28, $headerText, 'F1', 8.0, 0.5, 0.55, 0.62);
        $this->drawLine($this->marginLeft, $this->pageHeight - 32, $this->pageWidth - $this->marginRight, $this->pageHeight - 32, 0.85, 0.88, 0.92, 0.5);
        $this->currentY = $this->pageHeight - 50;
    }

    protected function renderTitle(string $title): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $lines = $this->wrapText($title, $w, 16.0);

        foreach ($lines as $line) {
            $this->drawText($this->marginLeft, $this->currentY, $line, 'F5', 15.0, 0.08, 0.12, 0.2);
            $this->currentY -= 19;
        }
        $this->currentY -= 6;
    }

    protected function renderAuthors(array $data): void
    {
        $authors = $data['authors'] ?? [];
        if (empty($authors)) {
            $authors = [['name' => $data['author_name'] ?? 'Academic Researcher', 'affiliation' => $data['author_affiliation'] ?? 'Institutional Scholar', 'is_corresponding' => true]];
        }

        // Author names line
        $names = [];
        foreach ($authors as $a) {
            $str = $a['name'] ?? '';
            if (!empty($a['is_corresponding'])) {
                $str .= ' *';
            }
            $names[] = $str;
        }
        $authorsLine = implode(', ', $names);

        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $lines = $this->wrapText($authorsLine, $w, 10.5);
        foreach ($lines as $line) {
            $this->drawText($this->marginLeft, $this->currentY, $line, 'F2', 10.5, 0.12, 0.23, 0.54);
            $this->currentY -= 14;
        }

        // Affiliations
        $affiliations = [];
        foreach ($authors as $idx => $a) {
            $aff = trim($a['affiliation'] ?? '');
            if ($aff !== '' && !in_array($aff, $affiliations)) {
                $affiliations[] = $aff;
            }
        }
        if (empty($affiliations)) {
            $affiliations[] = 'HJPARAM Research Division & Academic Affiliates';
        }

        foreach ($affiliations as $aff) {
            $lines = $this->wrapText($aff, $w, 9.0);
            foreach ($lines as $l) {
                $this->drawText($this->marginLeft, $this->currentY, $l, 'F3', 8.5, 0.35, 0.4, 0.45);
                $this->currentY -= 12;
            }
        }

        // Corresponding note
        $corrEmail = $data['author_email'] ?? ($authors[0]['email'] ?? '');
        if ($corrEmail) {
            $this->drawText($this->marginLeft, $this->currentY, "* Corresponding Author: {$corrEmail}", 'F1', 8.0, 0.45, 0.5, 0.55);
            $this->currentY -= 12;
        }
        $this->currentY -= 6;
    }

    protected function renderMetadata(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $boxHeight = 26.0;

        // Background box
        $this->drawRect($this->marginLeft, $this->currentY - $boxHeight, $w, $boxHeight, 0.96, 0.97, 0.99, true);
        $this->drawRect($this->marginLeft, $this->currentY - $boxHeight, $w, $boxHeight, 0.85, 0.88, 0.92, false);

        $received = $data['received_date'] ?? '10 Jan 2026';
        $accepted = $data['accepted_date'] ?? '24 Jan 2026';
        $published = $data['published_date'] ?? '30 Jan 2026';
        $doi = $data['doi'] ?? '';

        $textY = $this->currentY - 10;
        $metaDates = "Received: {$received}   |   Accepted: {$accepted}   |   Published: {$published}";
        $this->drawText($this->marginLeft + 10, $textY, $metaDates, 'F1', 8.0, 0.25, 0.3, 0.38);

        if ($doi) {
            $this->drawText($this->marginLeft + 10, $textY - 10, "DOI: https://doi.org/{$doi}", 'F2', 8.0, 0.12, 0.35, 0.75);
        }

        $this->currentY -= ($boxHeight + 16);
    }

    protected function renderAbstract(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $abstract = trim($data['abstract'] ?? 'No abstract provided.');

        // "ABSTRACT" heading
        $this->drawText($this->marginLeft, $this->currentY, 'ABSTRACT', 'F2', 10.0, 0.12, 0.23, 0.54);
        $this->currentY -= 13;

        // Abstract text inside padded indent
        $lines = $this->wrapText($abstract, $w - 12, 9.5);
        foreach ($lines as $line) {
            $this->checkPageBreak(13, $data);
            $this->drawText($this->marginLeft + 6, $this->currentY, $line, 'F6', 9.0, 0.18, 0.22, 0.28);
            $this->currentY -= 13;
        }
        $this->currentY -= 6;

        // Keywords
        if (!empty($data['keywords'])) {
            $this->checkPageBreak(14, $data);
            $kwText = 'Keywords: ' . $data['keywords'];
            $kwLines = $this->wrapText($kwText, $w, 8.5);
            foreach ($kwLines as $kwl) {
                $this->drawText($this->marginLeft, $this->currentY, $kwl, 'F2', 8.5, 0.25, 0.3, 0.35);
                $this->currentY -= 12;
            }
            $this->currentY -= 6;
        }

        $this->drawLine($this->marginLeft, $this->currentY, $this->pageWidth - $this->marginRight, $this->currentY, 0.88, 0.9, 0.94, 0.75);
        $this->currentY -= 16;
    }

    protected function renderBodySections(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;

        // If real extracted paragraphs from the author's manuscript exist, render the FULL paper
        $paragraphs = $data['body_paragraphs'] ?? [];
        if (!empty($paragraphs) && count($paragraphs) > 0) {
            foreach ($paragraphs as $p) {
                $text = trim($p['text'] ?? '');
                if ($text === '') {
                    continue;
                }

                $isHeading = !empty($p['is_heading']);
                if ($isHeading) {
                    $this->checkPageBreak(35, $data);
                    $this->currentY -= 6;
                    $lines = $this->wrapText($text, $w, 11.0);
                    foreach ($lines as $line) {
                        $this->checkPageBreak(16, $data);
                        $this->drawText($this->marginLeft, $this->currentY, $line, 'F2', 10.5, 0.12, 0.23, 0.54);
                        $this->currentY -= 15;
                    }
                    $this->currentY -= 4;
                } else {
                    $lines = $this->wrapText($text, $w, 9.5);
                    foreach ($lines as $line) {
                        $this->checkPageBreak(13, $data);
                        $this->drawText($this->marginLeft, $this->currentY, $line, 'F4', 9.0, 0.15, 0.18, 0.22);
                        $this->currentY -= 13;
                    }
                    $this->currentY -= 4;
                }
            }
            return;
        }

        $sections = [
            '1. Introduction & Academic Framework' => 'Academic inquiry within contemporary scholarly landscapes necessitates rigorous analytical frameworks and structured empirical validation. This study formulates a systematic investigation into the designated variables, synthesizing benchmark academic paradigms with applied methodologies.',
            '2. Methodology & Research Formulation' => 'The evaluative parameters were structured according to standard peer-reviewed protocols. Empirical models were calibrated to ensure high reproducibility, internal validity, and consistency across diversified contextual domains.',
            '3. Scholarly Analysis & Findings' => 'Data evaluation reveals statistically significant correlations across primary indices. The findings substantiate the hypothesized outcomes, offering actionable perspectives for academic researchers, institutional practitioners, and policy scholars.',
            '4. Conclusion & Future Scope' => 'The theoretical and applied insights documented in this manuscript contribute meaningful advancements to the disciplinary domain. Prospective research initiatives may expand observational scopes through extended multi-institutional collaborations.',
        ];

        foreach ($sections as $title => $body) {
            $this->checkPageBreak(40, $data);

            // Section Title
            $this->drawText($this->marginLeft, $this->currentY, $title, 'F2', 10.0, 0.12, 0.23, 0.54);
            $this->currentY -= 14;

            // Section Content
            $bodyLines = $this->wrapText($body, $w, 9.5);
            foreach ($bodyLines as $bl) {
                $this->checkPageBreak(13, $data);
                $this->drawText($this->marginLeft, $this->currentY, $bl, 'F4', 9.0, 0.2, 0.22, 0.26);
                $this->currentY -= 13;
            }
            $this->currentY -= 8;
        }
    }

    /**
     * Extract structured paragraphs and headings from a DOCX file or binary string.
     */
    public static function extractDocxParagraphs($source): array
    {
        $rawXml = null;

        // Try ZipArchive if available
        if (class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive();
            $tempFile = null;
            if (is_string($source) && !file_exists($source)) {
                $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
                file_put_contents($tempFile, $source);
                $fileToOpen = $tempFile;
            } else {
                $fileToOpen = $source;
            }

            if ($fileToOpen && file_exists($fileToOpen) && $zip->open($fileToOpen) === true) {
                $rawXml = $zip->getFromName('word/document.xml');
                $zip->close();
            }
            if ($tempFile && file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }

        // Pure PHP PKZip fallback without requiring any extensions
        if (empty($rawXml)) {
            $binary = is_string($source) && file_exists($source) ? file_get_contents($source) : (string)$source;
            $rawXml = self::extractZipEntry($binary, 'word/document.xml');
        }

        if (empty($rawXml)) {
            return [];
        }

        // Extract paragraphs and headings
        $paragraphs = [];
        if (preg_match_all('/<w:p(?:\s[^>]*)?>(.*?)<\/w:p>/s', $rawXml, $matches)) {
            foreach ($matches[1] as $pXml) {
                $isHeading = (bool)preg_match('/<w:pStyle\s[^>]*w:val="(?:Heading|Title)[^"]*"/i', $pXml);
                if (preg_match_all('/<w:t(?:\s[^>]*)?>([^<]*)<\/w:t>/s', $pXml, $tMatches)) {
                    $text = trim(implode('', $tMatches[1]));
                    if ($text !== '') {
                        $paragraphs[] = [
                            'text' => html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8'),
                            'is_heading' => $isHeading,
                        ];
                    }
                }
            }
        }

        return $paragraphs;
    }

    /**
     * Pure-PHP extraction of a specific entry from a PKZIP / DOCX binary stream.
     */
    protected static function extractZipEntry(string $zipData, string $targetFile): ?string
    {
        $pos = 0;
        $len = strlen($zipData);

        while ($pos < $len && ($pos = strpos($zipData, "PK\x03\x04", $pos)) !== false) {
            if ($pos + 30 > $len) break;
            $meta = @unpack('vver/vflags/vmethod/vtime/vdate/Vcrc/VcSize/VuSize/vnameLen/vextraLen', substr($zipData, $pos + 4, 26));
            if (!$meta) break;

            $namePos = $pos + 30;
            if ($namePos + $meta['nameLen'] > $len) break;
            $filename = substr($zipData, $namePos, $meta['nameLen']);

            $dataPos = $namePos + $meta['nameLen'] + $meta['extraLen'];
            if ($dataPos + $meta['cSize'] > $len) break;

            if ($filename === $targetFile) {
                $compData = substr($zipData, $dataPos, $meta['cSize']);
                if ($meta['method'] == 8) {
                    $decomp = @gzinflate($compData);
                    return $decomp !== false ? $decomp : null;
                }
                if ($meta['method'] == 0) {
                    return $compData;
                }
            }

            $pos = $dataPos + $meta['cSize'];
        }

        return null;
    }

    protected function compilePdf(array $data): string
    {
        $out = "%PDF-1.4\n";
        $out .= "%\xE2\xE3\xCF\xD3\n";

        $objects = [];
        $objIndex = 0;

        // Font objects
        // 1: Catalog
        // 2: Pages
        // 3: F1 (Helvetica)
        // 4: F2 (Helvetica-Bold)
        // 5: F3 (Helvetica-Oblique)
        // 6: F4 (Times-Roman)
        // 7: F5 (Times-Bold)
        // 8: F6 (Times-Italic)

        $pageObjIds = [];
        $contentObjIds = [];

        $fontObjStart = 3;
        $fonts = [
            'F1' => 'Helvetica',
            'F2' => 'Helvetica-Bold',
            'F3' => 'Helvetica-Oblique',
            'F4' => 'Times-Roman',
            'F5' => 'Times-Bold',
            'F6' => 'Times-Italic',
        ];

        $nextObjId = $fontObjStart + count($fonts);

        for ($p = 1; $p <= $this->pageCount; $p++) {
            $pageObjIds[$p] = $nextObjId++;
            $contentObjIds[$p] = $nextObjId++;
        }

        // Obj 1: Catalog
        $objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";

        // Obj 2: Pages
        $kidsStr = implode(' 0 R ', array_values($pageObjIds)) . ' 0 R';
        $objects[2] = sprintf("<< /Type /Pages /Kids [%s] /Count %d >>", $kidsStr, $this->pageCount);

        // Font objects
        $fIdx = $fontObjStart;
        $fontResStr = '';
        foreach ($fonts as $alias => $baseFont) {
            $objects[$fIdx] = sprintf("<< /Type /Font /Subtype /Type1 /BaseFont /%s /Encoding /WinAnsiEncoding >>", $baseFont);
            $fontResStr .= sprintf("/%s %d 0 R ", $alias, $fIdx);
            $fIdx++;
        }

        // Render each page
        for ($p = 1; $p <= $this->pageCount; $p++) {
            // Add page footer to the page content stream
            $footerY = 24.0;
            $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
            $footerLine = sprintf("%.2F w 0.85 0.88 0.92 RG %.2F %.2F m %.2F %.2F l S\n", 0.5, $this->marginLeft, $footerY + 10, $this->pageWidth - $this->marginRight, $footerY + 10);
            $footerNotice = sprintf("BT /F1 7.0 Tf 0.45 0.5 0.55 rg %.2F %.2F Td (%s) Tj ET\n", $this->marginLeft, $footerY + 2, $this->escape(sprintf("Copyright (c) %s Authors. Published by HJPARAM Publication under CC-BY 4.0. https://hjparam.com", $data['year'] ?? date('Y'))));
            $pageNumStr = sprintf("BT /F2 8.0 Tf 0.3 0.35 0.45 rg %.2F %.2F Td (%s) Tj ET\n", $this->pageWidth - $this->marginRight - 55, $footerY + 2, sprintf("Page %d of %d", $p, $this->pageCount));

            $streamContent = $this->pages[$p] . $footerLine . $footerNotice . $pageNumStr;
            $compressedStream = function_exists('gzcompress') ? gzcompress($streamContent, 9) : null;

            if ($compressedStream !== null && strlen($compressedStream) < strlen($streamContent)) {
                $objects[$contentObjIds[$p]] = sprintf("<< /Filter /FlateDecode /Length %d >>\nstream\n%s\nendstream", strlen($compressedStream), $compressedStream);
            } else {
                $objects[$contentObjIds[$p]] = sprintf("<< /Length %d >>\nstream\n%s\nendstream", strlen($streamContent), $streamContent);
            }

            // Page Object
            $objects[$pageObjIds[$p]] = sprintf(
                "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2F %.2F] /Contents %d 0 R /Resources << /Font << %s >> >> >>",
                $this->pageWidth,
                $this->pageHeight,
                $contentObjIds[$p],
                $fontResStr
            );
        }

        // Build PDF with offsets
        $offsets = [];
        $currentPos = strlen($out);

        ksort($objects);
        foreach ($objects as $id => $content) {
            $offsets[$id] = $currentPos;
            $chunk = sprintf("%d 0 obj\n%s\nendobj\n", $id, $content);
            $out .= $chunk;
            $currentPos += strlen($chunk);
        }

        // Cross-reference table
        $startXref = $currentPos;
        $maxId = max(array_keys($objects));
        $out .= sprintf("xref\n0 %d\n", $maxId + 1);
        $out .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            $off = $offsets[$i] ?? 0;
            $out .= sprintf("%010d 00000 n \n", $off);
        }

        $out .= sprintf("trailer\n<< /Size %d /Root 1 0 R >>\nstartxref\n%d\n%%%%EOF", $maxId + 1, $startXref);

        return $out;
    }
}

