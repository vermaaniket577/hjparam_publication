<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\AcademicPdfService;
use App\Services\DocxConverterService;
use App\Services\DocxToPdfService;
use App\Services\PureDocxPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display the specified article.
     */
    public function show($journalSlug, $articleSlug)
    {
        $article = Article::with(['journal', 'issue.volume', 'authors', 'certificates'])
            ->where('slug', $articleSlug)
            ->firstOrFail();

        // Increment Views
        $article->increment('views_count');

        return view('articles.show', compact('article'));
    }

    /**
     * Download or view the journal paper as a PDF preserving the exact formatting uploaded by admin/author.
     * - Priority 1: Exact uploaded PDF file on disk or submission binary_content.
     * - Priority 2: Faithful DOCX -> PDF conversion preserving exact headers, logos, footers, styles,
     *   tables, figures, badges, Abstract box, and 2-column layout (LibreOffice if available, else DomPDF).
     * - Priority 3: Fallback AcademicPdfService only if no uploaded file exists.
     */
    public function download(Request $request, $id)
    {
        // 1. If user explicitly requests the raw/original Word manuscript
        if ($request->has('raw') || $request->has('manuscript') || in_array(strtolower($request->get('format', '')), ['docx', 'doc', 'word'])) {
            return $this->downloadManuscript($request, $id);
        }

        $article = Article::with(['journal', 'issue.volume', 'authors', 'submission'])->findOrFail($id);

        // Security check: Only allow public download if article is published, or user is authenticated
        if ($article->status !== 'published' && !Auth::check()) {
            abort(403, 'This paper is not yet publicly released.');
        }

        // Track download analytics
        $this->recordDownload($article, $request);

        $journal = $article->journal;
        $issue = $article->issue;
        $volume = $issue ? $issue->volume : null;

        $volNum = $volume ? $volume->volume_number : '1';
        $issNum = $issue ? $issue->issue_number : '1';
        $cleanJournalTitle = Str::slug($journal ? $journal->title : 'journal');
        $cleanArticleTitle = Str::slug($article->title);
        $filename = "{$cleanJournalTitle}-vol-{$volNum}-issue-{$issNum}-{$cleanArticleTitle}.pdf";

        $noCacheHeaders = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        // 2. Priority 1: Exact uploaded PDF file on disk (Admin pdf_path or submission PDF when no Word override)
        $realPdfPath = $article->resolveRealPdfPath();
        if ($realPdfPath) {
            return response()->file($realPdfPath, array_merge($noCacheHeaders, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]));
        }

        // 3. Priority 2: Exact uploaded PDF in submission binary_content (only if admin did not upload a Word override on disk)
        $hasExplicitWordOverride = !empty($article->manuscript_path)
            && $article->locateFileOnDisk($article->manuscript_path) !== null;

        if (!$hasExplicitWordOverride) {
            $binStr = $article->getSubmissionBinaryContent();
            if ($article->isPdfBinary($binStr)) {
                return response($binStr, 200, array_merge($noCacheHeaders, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    'Content-Length' => strlen($binStr),
                ]));
            }
        }

        $issueYear = $volume && $volume->year ? $volume->year : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('Y') : date('Y'));
        $articleMeta = [
            'journal_title' => $journal ? $journal->title : 'HJPARAM Publication',
            'issn' => $journal && !empty($journal->issn) ? $journal->issn : 'Applied (Online)',
            'volume' => $volNum,
            'issue' => $issNum,
            'year' => $issueYear,
            'doi' => $article->doi ?? '',
        ];

        // 4. Priority 3: Convert uploaded Word document (.docx / .doc) on disk to a faithful PDF
        //    preserving the exact uploaded formatting (headers, logo, footers, styles, tables, figures, 2-column layout)
        $wordPath = $article->resolveWordManuscriptDiskPath();
        if ($wordPath && file_exists($wordPath)) {
            // 4a. Headless LibreOffice if installed and allowed on server
            try {
                $loPdf = DocxConverterService::convert($wordPath);
                if ($loPdf && file_exists($loPdf)) {
                    return response()->file($loPdf, array_merge($noCacheHeaders, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]));
                }
            } catch (\Throwable $e) {
                Log::warning('LibreOffice conversion skipped: ' . $e->getMessage());
            }

            // 4b. Zero-dependency Pure-PHP direct DOCX -> PDF renderer (works on all shared hosts, exact 2-col layout)
            try {
                $purePdfPath = PureDocxPdfService::convert($wordPath, false, $articleMeta);
                if ($purePdfPath && file_exists($purePdfPath)) {
                    return response()->file($purePdfPath, array_merge($noCacheHeaders, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]));
                }
            } catch (\Throwable $e) {
                Log::warning('PureDocxPdfService disk conversion error: ' . $e->getMessage());
            }

            // 4c. DomPDF HTML converter fallback if installed
            try {
                $domPdfPath = DocxToPdfService::convert($wordPath, false);
                if ($domPdfPath && file_exists($domPdfPath)) {
                    return response()->file($domPdfPath, array_merge($noCacheHeaders, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]));
                }
            } catch (\Throwable $e) {
                Log::warning('DocxToPdfService disk conversion error: ' . $e->getMessage());
            }
        }

        // 5. Priority 4: Convert uploaded Word document stored in submission binary_content to faithful PDF
        $binStr = $article->getSubmissionBinaryContent();
        if ($binStr !== null && !$article->isPdfBinary($binStr)) {
            try {
                $purePdfPath = PureDocxPdfService::convert($binStr, true, $articleMeta);
                if ($purePdfPath && file_exists($purePdfPath)) {
                    return response()->file($purePdfPath, array_merge($noCacheHeaders, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]));
                }
            } catch (\Throwable $e) {
                Log::warning('PureDocxPdfService binary conversion error: ' . $e->getMessage());
            }

            try {
                $domPdfPath = DocxToPdfService::convert($binStr, true);
                if ($domPdfPath && file_exists($domPdfPath)) {
                    return response()->file($domPdfPath, array_merge($noCacheHeaders, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]));
                }
            } catch (\Throwable $e) {
                Log::warning('DocxToPdfService binary conversion error: ' . $e->getMessage());
            }
        }

        // 6. Ultimate fallback ONLY when no convertible uploaded file exists for this article:
        //    Generate official academic PDF from metadata + extracted elements using AcademicPdfService
        $bodyElements = [];
        if ($wordPath && file_exists($wordPath)) {
            $bodyElements = AcademicPdfService::extractDocxElements($wordPath);
        } elseif ($binStr !== null && !$article->isPdfBinary($binStr)) {
            $bodyElements = AcademicPdfService::extractDocxElements($binStr);
        }

        $issueMonth = $volume && $volume->month ? $volume->month : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('F') : 'Regular');
        $issueYear = $volume && $volume->year ? $volume->year : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('Y') : date('Y'));

        try {
            $academicPdf = new AcademicPdfService();
            $pdfBytes = $academicPdf->generate([
                'title' => $article->title,
                'journal_title' => $journal ? $journal->title : 'HJPARAM Publication',
                'volume' => $volNum,
                'issue' => $issNum,
                'month' => $issueMonth,
                'year' => $issueYear,
                'issn' => $journal ? ($journal->issn ?: 'Applied (Online)') : 'Applied (Online)',
                'doi' => $article->doi ?? '',
                'authors' => $article->authors->map(function ($a) {
                    return [
                        'name' => $a->name,
                        'affiliation' => $a->affiliation ?? 'Academic Scholar',
                        'email' => $a->email ?? '',
                        'is_corresponding' => (bool)$a->is_corresponding,
                    ];
                })->toArray(),
                'author_name' => $article->submission?->user?->name ?? 'Academic Author',
                'author_affiliation' => $article->submission?->user?->affiliation ?? 'Academic Scholar',
                'author_email' => $article->submission?->user?->email ?? '',
                'abstract' => $article->abstract,
                'keywords' => $article->keywords,
                'body_elements' => $bodyElements,
                'received_date' => $article->submitted_at ? \Carbon\Carbon::parse($article->submitted_at)->format('d M Y') : '10 Jan 2026',
                'accepted_date' => $article->accepted_at ? \Carbon\Carbon::parse($article->accepted_at)->format('d M Y') : '24 Jan 2026',
                'published_date' => $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : date('d M Y'),
            ]);

            return response($pdfBytes, 200, array_merge($noCacheHeaders, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Content-Length' => strlen($pdfBytes),
            ]));
        } catch (\Throwable $e) {
            Log::error('AcademicPdfService generation error: ' . $e->getMessage());
        }

        // 6. Ultimate fallback: printable HTML view
        return response()->view('articles.pdf', compact('article', 'journal', 'issue', 'volume', 'issueMonth', 'issueYear'))
            ->header('Content-Type', 'text/html');
    }

    /**
     * Download the byte-for-byte exact original Microsoft Word document (.doc or .docx)
     * uploaded by the author/admin, without any conversion, template recreation, or style modification.
     */
    public function downloadManuscript(Request $request, $id)
    {
        $article = Article::with(['journal', 'issue.volume', 'authors', 'submission'])->findOrFail($id);

        // Security check: Only allow public download if article is published, or user is authenticated
        if ($article->status !== 'published' && !Auth::check()) {
            abort(403, 'This manuscript is not yet publicly released.');
        }

        $this->recordDownload($article, $request);

        $response = $this->buildExactManuscriptResponse($article);
        if ($response !== null) {
            return $response;
        }

        return back()->with('error', 'The original Word document for this paper is not currently available for download.');
    }

    /**
     * Track download analytics and increment article counter safely.
     */
    protected function recordDownload(Article $article, Request $request): void
    {
        try {
            DB::table('article_downloads')->insert([
                'article_id' => $article->id,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string)$request->userAgent(), 0, 500),
                'downloaded_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Non-critical
        }

        $article->increment('downloads_count');
    }

    /**
     * Build a byte-exact download response for the article's original uploaded Word document (.doc / .docx).
     */
    protected function buildExactManuscriptResponse(Article $article)
    {
        $noCacheHeaders = [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        // 1. Check for original Word manuscript on disk
        $diskPath = $article->resolveWordManuscriptDiskPath();
        if ($diskPath && file_exists($diskPath) && !is_dir($diskPath)) {
            $diskExt = strtolower(pathinfo($diskPath, PATHINFO_EXTENSION));
            $ext = $diskExt ?: strtolower($article->file_extension ?: 'docx');
            $downloadFilename = $article->original_filename ?: $article->manuscript_filename;
            $downloadFilename = str_replace(["\r", "\n", '"'], '', $downloadFilename);

            if (!str_ends_with(strtolower($downloadFilename), '.' . $ext)) {
                $downloadFilename .= '.' . $ext;
            }

            $mime = $this->resolveMimeType($ext, $article->mime_type);

            return response()->download($diskPath, $downloadFilename, array_merge($noCacheHeaders, [
                'Content-Type' => $mime,
            ]));
        }

        // 2. Check for original binary content in database (Submission binary storage)
        $binStr = $article->getSubmissionBinaryContent();
        if ($binStr !== null && !$article->isPdfBinary($binStr)) {
            $ext = strtolower($article->submission?->extension ?: $article->file_extension ?: 'docx');
            $downloadFilename = $article->original_filename ?: ($article->submission?->filename ?: (Str::slug($article->title) . '.' . $ext));
            $downloadFilename = str_replace(["\r", "\n", '"'], '', $downloadFilename);

            if (!str_ends_with(strtolower($downloadFilename), '.' . $ext)) {
                $downloadFilename .= '.' . $ext;
            }

            $mime = $this->resolveMimeType($ext, $article->submission?->mime_type);

            return response($binStr, 200, array_merge($noCacheHeaders, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'attachment; filename="' . addslashes($downloadFilename) . '"',
                'Content-Length' => strlen($binStr),
            ]));
        }

        return null;
    }

    /**
     * Resolve accurate MIME type for uploaded manuscript extensions.
     */
    protected function resolveMimeType(string $ext, ?string $fallback = null): string
    {
        return match (strtolower($ext)) {
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => $fallback ?: 'application/octet-stream',
        };
    }

    /**
     * Determine if a given file path is a genuine PDF by checking the %PDF magic bytes.
     */
    protected function isRealPdf(?string $path): bool
    {
        if (empty($path) || !file_exists($path) || is_dir($path)) {
            return false;
        }

        $h = @fopen($path, 'rb');
        if (!$h) {
            return false;
        }

        $magic = fread($h, 4);
        fclose($h);

        return $magic === '%PDF';
    }

    /**
     * Search possible storage locations and return the absolute path if it is a valid PDF.
     */
    protected function findRealPdfPath(?string $relPath): ?string
    {
        if (empty($relPath)) {
            return null;
        }

        $candidates = [
            Storage::disk('public')->path($relPath),
            Storage::disk('local')->path($relPath),
            public_path('storage/' . $relPath),
            public_path($relPath),
            storage_path('app/' . $relPath),
            storage_path('app/private/' . $relPath),
            storage_path('app/public/' . $relPath),
        ];

        foreach ($candidates as $cand) {
            if ($this->isRealPdf($cand)) {
                return $cand;
            }
        }

        return null;
    }

    /**
     * Find original manuscript file (e.g. docx, doc) across all possible storage roots.
     */
    protected function findRawManuscriptPath(Article $article): ?array
    {
        $relPaths = array_filter([
            $article->manuscript_path,
            $article->submission?->file_path,
            $article->pdf_path,
        ]);

        if (empty($relPaths)) {
            return null;
        }

        foreach ($relPaths as $relPath) {
            $candidates = [
                Storage::disk('local')->path($relPath),
                storage_path('app/private/' . $relPath),
                storage_path('app/' . $relPath),
                Storage::disk('public')->path($relPath),
                storage_path('app/public/' . $relPath),
                public_path('storage/' . $relPath),
                public_path($relPath),
            ];

            foreach ($candidates as $cand) {
                if (file_exists($cand) && !is_dir($cand)) {
                    $ext = strtolower(pathinfo($cand, PATHINFO_EXTENSION) ?: 'docx');
                    $cleanTitle = Str::slug($article->title);
                    return [
                        'path' => $cand,
                        'filename' => "{$cleanTitle}.{$ext}",
                    ];
                }
            }
        }

        return null;
    }
}
