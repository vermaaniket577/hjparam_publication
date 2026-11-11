<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $article = Article::with(['journal', 'issue.volume', 'authors'])->findOrFail($id);

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

        $issueMonth = $volume && $volume->month ? $volume->month : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('F') : 'Regular');
        $issueYear = $volume && $volume->year ? $volume->year : ($issue && $issue->publication_date ? \Carbon\Carbon::parse($issue->publication_date)->format('Y') : date('Y'));

        // If raw file explicitly requested and present on disk
        if ($request->has('raw') && $article->pdf_path && Storage::disk('public')->exists($article->pdf_path)) {
            return Storage::disk('public')->download($article->pdf_path);
        }

        // Generate official academic PDF with complete ISSN header:
        // Journal Title, Volume, Issue No., Month, Year, and ISSN
        return $this->generateOfficialPdf($article, $journal, $issue, $volume, $issueMonth, $issueYear);
    }

    protected function generateOfficialPdf($article, $journal, $issue, $volume, $issueMonth, $issueYear)
    {
        $html = view('articles.pdf', compact('article', 'journal', 'issue', 'volume', 'issueMonth', 'issueYear'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $volNum = $volume ? $volume->volume_number : '1';
        $issNum = $issue ? $issue->issue_number : '1';
        $cleanJournalTitle = Str::slug($journal ? $journal->title : 'journal');
        $cleanArticleTitle = Str::slug($article->title);
        $filename = "{$cleanJournalTitle}-vol-{$volNum}-issue-{$issNum}-{$cleanArticleTitle}.pdf";

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}

