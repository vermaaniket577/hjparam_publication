<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\DownloadLog;
use App\Services\AcademicPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function show($journalSlug, $articleSlug)
    {
        $article = Article::where('slug', $articleSlug)
            ->whereHas('journal', function ($query) use ($journalSlug) {
                $query->where('slug', $journalSlug);
            })
            ->with(['journal', 'authors', 'issue.volume'])
            ->firstOrFail();

        // Increment view count
        $article->increment('views_count');

        return view('articles.show', compact('article'));
    }

    public function download($id, Request $request)
    {
        $article = Article::with(['journal', 'issue.volume', 'authors', 'submission'])->findOrFail($id);

        // Log download
        try {
            DownloadLog::create([
                'article_id' => $article->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'downloaded_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Non-critical
        }

        $article->increment('downloads_count');

        $journal = $article->journal;
        $issue = $article->issue;
        $volume = $issue ? $issue->volume : null;

        $volNum = $volume ? $volume->volume_number : '1';
        $issNum = $issue ? $issue->issue_number : '1';
        $cleanJournalTitle = Str::slug($journal ? $journal->title : 'journal');
        $cleanArticleTitle = Str::slug($article->title);
        $filename = "{$cleanJournalTitle}-vol-{$volNum}-issue-{$issNum}-{$cleanArticleTitle}.pdf";

        // 1. If user explicitly requests the raw manuscript file (e.g. Word .docx)
        if ($request->has('raw') || $request->has('manuscript') || $request->get('format') === 'docx') {
            $rawDoc = $this->findRawManuscriptPath($article);
            if ($rawDoc && file_exists($rawDoc['path'])) {
                return response()->download($rawDoc['path'], $rawDoc['filename']);
            }
            if (!empty($article->submission?->binary_content)) {
                $ext = $article->submission->extension ?: 'docx';
                $fn = $cleanArticleTitle . '.' . $ext;
                return response($article->submission->binary_content)
                    ->header('Content-Type', $article->submission->mime_type ?: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                    ->header('Content-Disposition', 'attachment; filename="' . $fn . '"');
            }
        }

        // 2. Check if the article has a verified, real PDF manuscript on disk
        $realPdfPath = $this->findRealPdfPath($article->pdf_path);
        if ($realPdfPath) {
            return response()->file($realPdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        }

        // 3. Check if the linked submission has a verified, real PDF file on disk
        if ($article->submission) {
            $subPdf = $this->findRealPdfPath($article->submission->file_path);
            if ($subPdf) {
                return response()->file($subPdf, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            }

            // Check if submission binary_content is a real PDF
            if (!empty($article->submission->binary_content)) {
                $binStr = is_resource($article->submission->binary_content) ? stream_get_contents($article->submission->binary_content) : (string)$article->submission->binary_content;
                if (str_starts_with($binStr, '%PDF')) {
                    return response($binStr, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]);
                }
            }
        }

        // 4. Extract complete paper paragraphs and sections from the author's uploaded manuscript
        $bodyParagraphs = [];
        $rawDoc = $this->findRawManuscriptPath($article);
        if ($rawDoc && file_exists($rawDoc['path'])) {
            $bodyParagraphs = AcademicPdfService::extractDocxParagraphs($rawDoc['path']);
        } elseif (!empty($article->submission?->binary_content)) {
            $binStr = is_resource($article->submission->binary_content) ? stream_get_contents($article->submission->binary_content) : (string)$article->submission->binary_content;
            $bodyParagraphs = AcademicPdfService::extractDocxParagraphs($binStr);
        }

        // 5. Generate complete multi-page official academic PDF using AcademicPdfService
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
                'body_paragraphs' => $bodyParagraphs,
                'received_date' => $article->submitted_at ? \Carbon\Carbon::parse($article->submitted_at)->format('d M Y') : '10 Jan 2026',
                'accepted_date' => $article->accepted_at ? \Carbon\Carbon::parse($article->accepted_at)->format('d M Y') : '24 Jan 2026',
                'published_date' => $article->published_at ? \Carbon\Carbon::parse($article->published_at)->format('d M Y') : date('d M Y'),
            ]);

            return response($pdfBytes, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Content-Length' => strlen($pdfBytes),
                'Cache-Control' => 'private, max-age=3600, must-revalidate',
            ]);
        } catch (\Throwable $e) {
            Log::error('AcademicPdfService generation error: ' . $e->getMessage());
        }

        // 5. Ultimate fallback: printable HTML view
        return response()->view('articles.pdf', compact('article', 'journal', 'issue', 'volume', 'issueMonth', 'issueYear'))
            ->header('Content-Type', 'text/html');
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
        ];

        foreach ($candidates as $cand) {
            if ($this->isRealPdf($cand)) {
                return $cand;
            }
        }

        return null;
    }

    /**
     * Find original manuscript file (e.g. docx, doc) for explicit download requests.
     */
    protected function findRawManuscriptPath(Article $article): ?array
    {
        $relPath = $article->pdf_path ?: ($article->submission?->file_path ?? null);
        if (empty($relPath)) {
            return null;
        }

        $candidates = [
            Storage::disk('public')->path($relPath),
            Storage::disk('local')->path($relPath),
            public_path('storage/' . $relPath),
            public_path($relPath),
            storage_path('app/' . $relPath),
        ];

        foreach ($candidates as $cand) {
            if (file_exists($cand) && !is_dir($cand)) {
                $ext = pathinfo($cand, PATHINFO_EXTENSION) ?: 'docx';
                $cleanTitle = Str::slug($article->title);
                return [
                    'path' => $cand,
                    'filename' => "{$cleanTitle}.{$ext}",
                ];
            }
        }

        return null;
    }
}

