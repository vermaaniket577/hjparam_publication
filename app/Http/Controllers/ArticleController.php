<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\DownloadLog;
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

        // 1. Check if the article has an uploaded official manuscript PDF
        if (!empty($article->pdf_path)) {
            if (Storage::disk('public')->exists($article->pdf_path)) {
                return response()->file(Storage::disk('public')->path($article->pdf_path), [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            }

            if (Storage::disk('local')->exists($article->pdf_path)) {
                return response()->file(Storage::disk('local')->path($article->pdf_path), [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            }

            if (file_exists(public_path('storage/' . $article->pdf_path))) {
                return response()->file(public_path('storage/' . $article->pdf_path), [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            }

            if (file_exists(public_path($article->pdf_path))) {
                return response()->file(public_path($article->pdf_path), [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            }
        }

        // 2. Fallback to attached submission PDF if available
        if ($article->submission) {
            $sub = $article->submission;
            if (!empty($sub->file_path)) {
                if (Storage::disk('public')->exists($sub->file_path)) {
                    return response()->file(Storage::disk('public')->path($sub->file_path), [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]);
                }

                if (Storage::disk('local')->exists($sub->file_path)) {
                    return response()->file(Storage::disk('local')->path($sub->file_path), [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]);
                }

                if (file_exists(storage_path('app/' . $sub->file_path))) {
                    return response()->file(storage_path('app/' . $sub->file_path), [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $filename . '"',
                    ]);
                }
            }

            if (!empty($sub->binary_content) && ($sub->extension === 'pdf' || $sub->mime_type === 'application/pdf')) {
                return response($sub->binary_content, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            }
        }

        // 3. Dynamic PDF Generation using Dompdf if installed, or fallback to printable HTML
        $issueMonth = $volume && $volume->month ? $volume->month : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('F') : 'Regular');
        $issueYear = $volume && $volume->year ? $volume->year : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('Y') : date('Y'));

        return $this->generateOfficialPdf($article, $journal, $issue, $volume, $issueMonth, $issueYear, $filename);
    }

    protected function generateOfficialPdf($article, $journal, $issue, $volume, $issueMonth, $issueYear, $filename = null)
    {
        $volNum = $volume ? $volume->volume_number : '1';
        $issNum = $issue ? $issue->issue_number : '1';
        $cleanJournalTitle = Str::slug($journal ? $journal->title : 'journal');
        $cleanArticleTitle = Str::slug($article->title);
        $filename = $filename ?: "{$cleanJournalTitle}-vol-{$volNum}-issue-{$issNum}-{$cleanArticleTitle}.pdf";

        // Check if Dompdf is loaded in vendor
        if (class_exists(\Dompdf\Dompdf::class) && class_exists(\Dompdf\Options::class)) {
            try {
                $html = view('articles.pdf', compact('article', 'journal', 'issue', 'volume', 'issueMonth', 'issueYear'))->render();

                $options = new \Dompdf\Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true);

                $dompdf = new \Dompdf\Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                return response($dompdf->output(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]);
            } catch (\Throwable $e) {
                Log::warning('Dompdf generation failed: ' . $e->getMessage());
            }
        }

        // Clean printable HTML fallback when Dompdf is not installed or fails
        return response()->view('articles.pdf', compact('article', 'journal', 'issue', 'volume', 'issueMonth', 'issueYear'))
            ->header('Content-Type', 'text/html');
    }
}

