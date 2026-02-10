<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function download($id)
    {
        $article = Article::findOrFail($id);

        // Log download
        DownloadLog::create([
            'article_id' => $article->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        $article->increment('downloads_count');

        // Logic for PDF download
        if ($article->pdf_path && Storage::exists($article->pdf_path)) {
            return Storage::download($article->pdf_path);
        }

        // Demo fallback: Generate a dummy PDF file content
        $content = "%PDF-1.4\n1 0 obj\n<< /Title (Article PDF) /Author (HJPARAM) >>\nendobj\n2 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n3 0 obj\n<< /Type /Pages /Kids [4 0 R] /Count 1 >>\nendobj\n4 0 obj\n<< /Type /Page /Parent 3 0 R /MediaBox [0 0 612 792] /Contents 5 0 R >>\nendobj\n5 0 obj\n<< /Length 44 >>\nstream\nBT /F1 12 Tf 72 712 Td (HJPARAM Publication: {$article->title}) Tj ET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000062 00000 n\n0000000109 00000 n\n0000000166 00000 n\n0000000263 00000 n\ntrailer\n<< /Size 6 /Root 2 0 R >>\nstartxref\n356\n%%EOF";
        $filename = "article-{$article->id}.pdf";

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
