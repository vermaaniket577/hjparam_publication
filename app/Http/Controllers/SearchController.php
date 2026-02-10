<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $author = $request->input('author');
        $journalId = $request->input('journal');
        $yearFrom = $request->input('year_from');
        $yearTo = $request->input('year_to');

        $articlesQuery = Article::where('status', 'published')
            ->with(['journal', 'authors']);

        if ($query) {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('abstract', 'like', "%{$query}%")
                    ->orWhere('keywords', 'like', "%{$query}%")
                    ->orWhere('doi', 'like', "%{$query}%");
            });
        }

        if ($author) {
            $articlesQuery->whereHas('authors', function ($q) use ($author) {
                $q->where('name', 'like', "%{$author}%");
            });
        }

        if ($journalId) {
            $articlesQuery->where('journal_id', $journalId);
        }

        if ($yearFrom) {
            $articlesQuery->whereYear('published_at', '>=', $yearFrom);
        }

        if ($yearTo) {
            $articlesQuery->whereYear('published_at', '<=', $yearTo);
        }

        $articles = $articlesQuery->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        return view('search.index', compact('articles', 'query'));
    }

    public function advanced()
    {
        return view('search.advanced');
    }
}
