<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DocxConverterService
{
    /**
     * Convert a Word document (.docx / .doc) to PDF with exact fidelity
     * (preserving all images, diagrams, tables, watermarks, and layout).
     *
     * Uses headless LibreOffice / soffice CLI if available and permitted on the server.
     * Caches the converted PDF for instant subsequent downloads.
     */
    public static function convert(string $docxPath): ?string
    {
        if (empty($docxPath) || !file_exists($docxPath) || is_dir($docxPath)) {
            return null;
        }

        // Cache check
        $cacheDir = storage_path('app/private/articles/pdf');
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $hash = md5($docxPath . '_' . filemtime($docxPath));
        $cachedPdf = $cacheDir . '/converted_' . $hash . '.pdf';

        if (file_exists($cachedPdf) && filesize($cachedPdf) > 1000) {
            return $cachedPdf;
        }

        // If shell execution functions are disabled in php.ini, exit safely
        if (!function_exists('exec') && !function_exists('shell_exec')) {
            return null;
        }

        try {
            // Find LibreOffice / soffice executable
            $binaries = [
                '/usr/bin/soffice',
                '/usr/bin/libreoffice',
                '/usr/local/bin/soffice',
                '/usr/local/bin/libreoffice',
                '/opt/libreoffice/program/soffice',
                'C:\Program Files\LibreOffice\program\soffice.exe',
                'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
            ];

            $usableBinary = null;
            foreach ($binaries as $bin) {
                if (file_exists($bin) && is_executable($bin)) {
                    $usableBinary = $bin;
                    break;
                }
            }

            if (!$usableBinary && function_exists('shell_exec')) {
                $which = @shell_exec('which soffice 2>/dev/null') ?: @shell_exec('which libreoffice 2>/dev/null');
                if (!empty($which) && !str_contains($which, 'not found') && !str_contains($which, 'no soffice')) {
                    $usableBinary = trim($which);
                }
            }

            if ($usableBinary && function_exists('exec')) {
                $tempOutDir = sys_get_temp_dir() . '/docx_pdf_' . time() . '_' . Str::random(6);
                @mkdir($tempOutDir, 0777, true);

                $cmd = sprintf(
                    '%s --headless --convert-to pdf:writer_pdf_Export --outdir %s %s 2>&1',
                    escapeshellcmd($usableBinary),
                    escapeshellarg($tempOutDir),
                    escapeshellarg($docxPath)
                );

                @exec($cmd, $output, $code);

                $baseName = pathinfo($docxPath, PATHINFO_FILENAME);
                $generated = $tempOutDir . '/' . $baseName . '.pdf';

                if (file_exists($generated) && filesize($generated) > 1000) {
                    @copy($generated, $cachedPdf);
                    @unlink($generated);
                    @rmdir($tempOutDir);
                    return $cachedPdf;
                }

                $pdfs = glob($tempOutDir . '/*.pdf');
                if (!empty($pdfs) && file_exists($pdfs[0]) && filesize($pdfs[0]) > 1000) {
                    @copy($pdfs[0], $cachedPdf);
                    @unlink($pdfs[0]);
                    @rmdir($tempOutDir);
                    return $cachedPdf;
                }

                @rmdir($tempOutDir);
            }
        } catch (\Throwable $e) {
            Log::warning('Docx conversion skipped: ' . $e->getMessage());
        }

        return null;
    }
}
