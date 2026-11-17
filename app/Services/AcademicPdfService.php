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
    protected array $images = []; // [id => ['data' => ..., 'w' => ..., 'h' => ...]]
    protected int $imageCounter = 0;

    public function generate(array $data): string
    {
        $this->pages = [];
        $this->offsets = [];
        $this->buffer = '';
        $this->pageCount = 0;
        $this->images = [];
        $this->imageCounter = 0;

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

        // Body Sections, Tables, Images & Diagrams
        $this->renderBodyContent($data);

        // Compile PDF objects
        return $this->compilePdf($data);
    }

    protected function addPage(array $data): void
    {
        $this->pageCount++;
        $this->pages[$this->pageCount] = '';
        $this->currentY = $this->pageHeight - $this->marginTop;

        // Render Academic Background Watermark on every page
        $this->renderWatermark($data);
    }

    protected function renderWatermark(array $data): void
    {
        $journal = $data['journal_title'] ?? 'HJPARAM ACADEMIC PRESS';
        $wmText = strtoupper(substr($this->escape($journal), 0, 36));

        // Diagonal faint background watermark (45 degree rotation)
        $this->appendToPage("q");
        $this->appendToPage("0.935 0.945 0.965 rg"); // Soft academic faint tint
        $this->appendToPage("0.7071 0.7071 -0.7071 0.7071 130 240 cm");
        $this->appendToPage(sprintf("BT /F2 26.0 Tf (%s) Tj ET", $wmText));
        $this->appendToPage("Q");
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
        $avgCharWidth = $fontSize * 0.50;

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

        // Top decorative accent bar
        $this->drawRect($this->marginLeft, $this->currentY - 2, $w, 3, 0.12, 0.23, 0.54, true);
        $this->currentY -= 16;

        // Journal Title
        $this->drawText($this->marginLeft, $this->currentY, $journalTitle, 'F2', 13.0, 0.12, 0.23, 0.54);

        // Header right badges
        $this->drawText($this->pageWidth - $this->marginRight - 130, $this->currentY + 2, 'HJPARAM ACADEMIC PRESS', 'F2', 8.0, 0.2, 0.2, 0.3);
        $this->drawText($this->pageWidth - $this->marginRight - 130, $this->currentY - 9, 'OPEN ACCESS  •  PEER-REVIEWED', 'F2', 7.0, 0.08, 0.55, 0.25);
        $this->currentY -= 14;

        // Sub-strip: Volume, Issue, ISSN
        $metaStrip = "Volume {$volNum}  |  Issue No. {$issueNum}  |  {$issueMonth} {$issueYear}  |  ISSN: {$issn}";
        $this->drawText($this->marginLeft, $this->currentY, $metaStrip, 'F1', 8.5, 0.35, 0.4, 0.48);
        $this->currentY -= 8;

        // Divider rule
        $this->drawLine($this->marginLeft, $this->currentY, $this->pageWidth - $this->marginRight, $this->currentY, 0.8, 0.85, 0.9, 1.0);
        $this->currentY -= 18;
    }

    protected function renderRunningHeader(array $data): void
    {
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

        $names = [];
        foreach ($authors as $a) {
            $str = $a['name'] ?? '';
            if (!empty($a['is_corresponding'])) {
                $str .= ' *';
            }
            $names[] = $str;
        }
        $authorLine = implode(', ', $names);

        $this->drawText($this->marginLeft, $this->currentY, $authorLine, 'F2', 10.0, 0.12, 0.23, 0.54);
        $this->currentY -= 14;

        $affiliations = [];
        $emails = [];
        foreach ($authors as $a) {
            if (!empty($a['affiliation']) && !in_array($a['affiliation'], $affiliations)) {
                $affiliations[] = $a['affiliation'];
            }
            if (!empty($a['email']) && !empty($a['is_corresponding'])) {
                $emails[] = $a['email'];
            }
        }

        if (!empty($affiliations)) {
            $affStr = implode(' | ', $affiliations);
            $this->drawText($this->marginLeft, $this->currentY, $affStr, 'F1', 8.5, 0.35, 0.4, 0.45);
            $this->currentY -= 12;
        }

        if (!empty($emails)) {
            $emailStr = "* Corresponding Author: " . implode(', ', $emails);
            $this->drawText($this->marginLeft, $this->currentY, $emailStr, 'F1', 8.0, 0.45, 0.5, 0.55);
            $this->currentY -= 14;
        } else {
            $this->currentY -= 4;
        }
    }

    protected function renderMetadata(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $boxHeight = 22.0;

        $this->drawRect($this->marginLeft, $this->currentY - $boxHeight + 4, $w, $boxHeight, 0.96, 0.97, 0.99, true);
        $this->drawRect($this->marginLeft, $this->currentY - $boxHeight + 4, $w, $boxHeight, 0.85, 0.88, 0.94, false);

        $recDate = $data['received_date'] ?? '10 Jan 2026';
        $accDate = $data['accepted_date'] ?? '24 Jan 2026';
        $pubDate = $data['published_date'] ?? date('d M Y');
        $doi = $data['doi'] ?? '';

        $datesStr = "Received: {$recDate}  |  Accepted: {$accDate}  |  Published: {$pubDate}";
        $this->drawText($this->marginLeft + 10, $this->currentY - 3, $datesStr, 'F1', 8.0, 0.3, 0.35, 0.4);

        if ($doi) {
            $doiStr = "DOI: https://doi.org/{$doi}";
            $this->drawText($this->marginLeft + 10, $this->currentY - 12, $doiStr, 'F2', 7.5, 0.12, 0.23, 0.54);
        }

        $this->currentY -= ($boxHeight + 14);
    }

    protected function renderAbstract(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $abstract = $data['abstract'] ?? '';
        $keywords = $data['keywords'] ?? '';

        $this->drawText($this->marginLeft, $this->currentY, 'ABSTRACT', 'F2', 9.5, 0.12, 0.23, 0.54);
        $this->currentY -= 13;

        if ($abstract) {
            $lines = $this->wrapText($abstract, $w, 9.5);
            foreach ($lines as $line) {
                $this->checkPageBreak(13, $data);
                $this->drawText($this->marginLeft, $this->currentY, $line, 'F6', 9.0, 0.2, 0.22, 0.25);
                $this->currentY -= 12.5;
            }
            $this->currentY -= 6;
        }

        if ($keywords) {
            $this->checkPageBreak(16, $data);
            $kwLine = "Keywords: " . $keywords;
            $kwLines = $this->wrapText($kwLine, $w, 8.5);
            foreach ($kwLines as $kwl) {
                $this->checkPageBreak(12, $data);
                $this->drawText($this->marginLeft, $this->currentY, $kwl, 'F2', 8.0, 0.3, 0.35, 0.45);
                $this->currentY -= 11.5;
            }
            $this->currentY -= 10;
        }

        $this->drawLine($this->marginLeft, $this->currentY, $this->pageWidth - $this->marginRight, $this->currentY, 0.88, 0.9, 0.94, 0.75);
        $this->currentY -= 16;
    }

    protected function renderBodyContent(array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $elements = $data['body_elements'] ?? [];

        // If elements is empty, check legacy body_paragraphs
        if (empty($elements) && !empty($data['body_paragraphs'])) {
            $elements = array_map(fn($p) => array_merge(['type' => 'paragraph'], $p), $data['body_paragraphs']);
        }

        if (!empty($elements)) {
            foreach ($elements as $el) {
                $type = $el['type'] ?? 'paragraph';

                if ($type === 'table') {
                    $this->renderTable($el, $data);
                } elseif ($type === 'image') {
                    $this->renderImageElement($el, $data);
                } else {
                    $text = trim($el['text'] ?? '');
                    if ($text === '') continue;

                    $isHeading = !empty($el['is_heading']);
                    if ($isHeading) {
                        $this->checkPageBreak(35, $data);
                        $this->currentY -= 8;
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
            }
            return;
        }

        // Standard Scholarly Fallback Sections
        $sections = [
            '1. Introduction & Academic Framework' => 'Academic inquiry within contemporary scholarly landscapes necessitates rigorous analytical frameworks and structured empirical validation. This study formulates a systematic investigation into the designated variables, synthesizing benchmark academic paradigms with applied methodologies.',
            '2. Methodology & Research Formulation' => 'The evaluative parameters were structured according to standard peer-reviewed protocols. Empirical models were calibrated to ensure high reproducibility, internal validity, and consistency across diversified contextual domains.',
            '3. Scholarly Analysis & Findings' => 'Data evaluation reveals statistically significant correlations across primary indices. The findings substantiate the hypothesized outcomes, offering actionable perspectives for academic researchers, institutional practitioners, and policy scholars.',
            '4. Conclusion & Future Scope' => 'The theoretical and applied insights documented in this manuscript contribute meaningful advancements to the disciplinary domain. Prospective research initiatives may expand observational scopes through extended multi-institutional collaborations.',
        ];

        foreach ($sections as $title => $body) {
            $this->checkPageBreak(40, $data);
            $this->drawText($this->marginLeft, $this->currentY, $title, 'F2', 10.0, 0.12, 0.23, 0.54);
            $this->currentY -= 14;

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
     * Render a structured academic table from Word document.
     */
    protected function renderTable(array $tableData, array $data): void
    {
        $rows = $tableData['rows'] ?? [];
        if (empty($rows)) return;

        $colCount = max(array_map('count', $rows));
        if ($colCount === 0) return;

        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $colWidth = $w / $colCount;

        $this->currentY -= 6;
        $this->checkPageBreak(25, $data);

        // Optional Table Title
        if (!empty($tableData['title'])) {
            $this->drawText($this->marginLeft, $this->currentY, $tableData['title'], 'F2', 8.5, 0.12, 0.23, 0.54);
            $this->currentY -= 12;
        }

        foreach ($rows as $rowIndex => $row) {
            $isHeader = ($rowIndex === 0);
            $rowHeight = $isHeader ? 20.0 : 17.0;

            $this->checkPageBreak($rowHeight + 8.0, $data);

            // Row background fill
            $bgR = $isHeader ? 0.92 : ($rowIndex % 2 === 0 ? 0.98 : 1.0);
            $bgG = $isHeader ? 0.94 : ($rowIndex % 2 === 0 ? 0.98 : 1.0);
            $bgB = $isHeader ? 0.98 : ($rowIndex % 2 === 0 ? 0.98 : 1.0);
            $this->drawRect($this->marginLeft, $this->currentY - $rowHeight + 4.0, $w, $rowHeight, $bgR, $bgG, $bgB, true);

            // Row outer border
            $this->drawRect($this->marginLeft, $this->currentY - $rowHeight + 4.0, $w, $rowHeight, 0.82, 0.86, 0.92, false);

            // Render cell text and vertical borders
            foreach ($row as $colIndex => $cellText) {
                $cellX = $this->marginLeft + ($colIndex * $colWidth);
                $cellY = $this->currentY - ($isHeader ? 10.0 : 8.5);
                $font = $isHeader ? 'F2' : 'F1';
                $fontSize = $isHeader ? 8.0 : 7.5;
                $tc = $isHeader ? [0.08, 0.18, 0.4] : [0.2, 0.22, 0.25];

                $cleanCell = mb_strimwidth(trim($cellText), 0, max(8, (int)($colWidth / 4.8)), '..');
                $this->drawText($cellX + 4.0, $cellY, $cleanCell, $font, $fontSize, $tc[0], $tc[1], $tc[2]);

                if ($colIndex > 0) {
                    $this->drawLine($cellX, $this->currentY + 4.0, $cellX, $this->currentY - $rowHeight + 4.0, 0.85, 0.88, 0.92, 0.5);
                }
            }

            $this->currentY -= $rowHeight;
        }

        $this->currentY -= 12.0;
    }

    /**
     * Render an image or diagram illustration extracted from the manuscript.
     */
    protected function renderImageElement(array $imageEl, array $data): void
    {
        $w = $this->pageWidth - $this->marginLeft - $this->marginRight;
        $caption = $imageEl['caption'] ?? 'Figure: Manuscript Diagram / Research Illustration';

        $boxHeight = 120.0;
        $boxWidth = min($w, 360.0);
        $boxX = $this->marginLeft + (($w - $boxWidth) / 2);

        $this->checkPageBreak($boxHeight + 35.0, $data);

        // Frame for diagram/figure
        $this->drawRect($boxX, $this->currentY - $boxHeight, $boxWidth, $boxHeight, 0.97, 0.98, 0.99, true);
        $this->drawRect($boxX, $this->currentY - $boxHeight, $boxWidth, $boxHeight, 0.82, 0.86, 0.92, false);

        // Figure placeholder illustration inside frame
        $centerX = $boxX + ($boxWidth / 2);
        $centerY = $this->currentY - ($boxHeight / 2);
        $this->drawText($centerX - 60, $centerY + 8, '[ RESEARCH DIAGRAM / FIGURE ]', 'F2', 8.5, 0.35, 0.45, 0.6);
        $this->drawText($centerX - 80, $centerY - 8, 'Original High-Resolution Graphic In Document', 'F1', 7.5, 0.5, 0.55, 0.62);

        $this->currentY -= ($boxHeight + 10.0);

        // Figure Caption
        $this->drawText($boxX, $this->currentY, $caption, 'F2', 8.0, 0.2, 0.25, 0.35);
        $this->currentY -= 16.0;
    }

    /**
     * Extract rich structured content (paragraphs, headings, tables, diagrams) from a DOCX file.
     */
    public static function extractDocxElements($source): array
    {
        $rawXml = null;
        $zipBinary = is_string($source) && file_exists($source) ? file_get_contents($source) : (string)$source;

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
        if (empty($rawXml) && !empty($zipBinary)) {
            $rawXml = self::extractZipEntry($zipBinary, 'word/document.xml');
        }

        if (empty($rawXml)) {
            return [];
        }

        $elements = [];

        // Parse <body> children in sequential order: <w:p> and <w:tbl>
        if (preg_match('/<w:body(?:\s[^>]*)?>(.*?)<\/w:body>/s', $rawXml, $bodyMatch)) {
            $bodyContent = $bodyMatch[1];

            // Match either <w:p> or <w:tbl>
            if (preg_match_all('/(<w:p(?:\s[^>]*)?>.*?<\/w:p>|<w:tbl(?:\s[^>]*)?>.*?<\/w:tbl>)/s', $bodyContent, $nodes)) {
                $figCounter = 1;
                $tblCounter = 1;

                foreach ($nodes[1] as $nodeXml) {
                    if (str_starts_with($nodeXml, '<w:tbl')) {
                        // Extract Table
                        $rows = [];
                        if (preg_match_all('/<w:tr(?:\s[^>]*)?>(.*?)<\/w:tr>/s', $nodeXml, $trMatches)) {
                            foreach ($trMatches[1] as $trXml) {
                                $cells = [];
                                if (preg_match_all('/<w:tc(?:\s[^>]*)?>(.*?)<\/w:tc>/s', $trXml, $tcMatches)) {
                                    foreach ($tcMatches[1] as $tcXml) {
                                        $cellText = '';
                                        if (preg_match_all('/<w:t(?:\s[^>]*)?>([^<]*)<\/w:t>/s', $tcXml, $tMatches)) {
                                            $cellText = trim(implode(' ', $tMatches[1]));
                                        }
                                        $cells[] = html_entity_decode($cellText, ENT_QUOTES | ENT_XML1, 'UTF-8');
                                    }
                                }
                                if (!empty($cells)) {
                                    $rows[] = $cells;
                                }
                            }
                        }

                        if (!empty($rows)) {
                            $elements[] = [
                                'type' => 'table',
                                'title' => "Table {$tblCounter}: Extracted Research Dataset",
                                'rows' => $rows,
                            ];
                            $tblCounter++;
                        }
                    } else {
                        // Check if paragraph contains drawing/image
                        $hasDrawing = str_contains($nodeXml, '<w:drawing') || str_contains($nodeXml, '<w:pict');
                        if ($hasDrawing) {
                            $elements[] = [
                                'type' => 'image',
                                'caption' => "Figure {$figCounter}: Manuscript Diagram / Empirical Visual",
                            ];
                            $figCounter++;
                        }

                        // Extract text
                        $isHeading = (bool)preg_match('/<w:pStyle\s[^>]*w:val="(?:Heading|Title)[^"]*"/i', $nodeXml);
                        if (preg_match_all('/<w:t(?:\s[^>]*)?>([^<]*)<\/w:t>/s', $nodeXml, $tMatches)) {
                            $text = trim(implode('', $tMatches[1]));
                            if ($text !== '') {
                                $elements[] = [
                                    'type' => 'paragraph',
                                    'text' => html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8'),
                                    'is_heading' => $isHeading,
                                ];
                            }
                        }
                    }
                }
            }
        }

        return $elements;
    }

    public static function extractDocxParagraphs($source): array
    {
        return self::extractDocxElements($source);
    }

    /**
     * Pure-PHP extraction of a specific entry from a PKZIP / DOCX binary stream.
     */
    protected static function extractZipEntry(string $zipData, string $targetFile): ?string
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

        // Direct scan
        $pos = 0;
        while (($pos = strpos($zipData, "PK\x03\x04", $pos)) !== false) {
            if ($pos + 30 > $len) break;
            $header = @unpack('vver/vflags/vmethod/vtime/vdate/Vcrc/VcSize/VuSize/vnameLen/vextraLen', substr($zipData, $pos + 4, 26));
            if (!$header) break;

            $filename = substr($zipData, $pos + 30, $header['nameLen']);
            $dataStart = $pos + 30 + $header['nameLen'] + $header['extraLen'];

            if ($filename === $targetFile) {
                $compressed = substr($zipData, $dataStart, $header['cSize']);
                if ($header['method'] == 8 && function_exists('gzinflate')) {
                    $unzipped = @gzinflate($compressed);
                    if ($unzipped) return $unzipped;
                } elseif ($header['method'] == 0) {
                    return $compressed;
                }
            }
            $pos = $dataStart + max($header['cSize'], 1);
        }

        return null;
    }

    protected function compilePdf(array $data): string
    {
        $out = "%PDF-1.4\n";
        $out .= "%\xE2\xE3\xCF\xD3\n";

        $objects = [];
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
            $footerY = 24.0;
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

            $objects[$pageObjIds[$p]] = sprintf(
                "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2F %.2F] /Contents %d 0 R /Resources << /Font << %s >> >> >>",
                $this->pageWidth,
                $this->pageHeight,
                $contentObjIds[$p],
                $fontResStr
            );
        }

        $offsets = [];
        $currentPos = strlen($out);

        ksort($objects);
        foreach ($objects as $id => $content) {
            $offsets[$id] = $currentPos;
            $chunk = sprintf("%d 0 obj\n%s\nendobj\n", $id, $content);
            $out .= $chunk;
            $currentPos += strlen($chunk);
        }

        $startXref = $currentPos;
        $maxId = max(array_keys($objects));
        $out .= sprintf("xref\n0 %d\n", $maxId + 1);
        $out .= "0000000000 65535 f \n";
        for ($i = 1; $i <= $maxId; $i++) {
            $out .= sprintf("%010d 00000 n \n", $offsets[$i] ?? 0);
        }

        $out .= sprintf("trailer\n<< /Size %d /Root 1 0 R >>\nstartxref\n%d\n%%%%EOF", $maxId + 1, $startXref);

        return $out;
    }
}
