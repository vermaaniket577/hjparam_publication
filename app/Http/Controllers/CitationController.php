<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class CitationController extends Controller
{
    public function downloadBibtex(Article $article)
    {
        $filename = "citation-" . $article->id . ".bib";
        $content = $article->bibtex;

        return response($content, 200, [
            'Content-Type' => 'application/x-bibtex',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
