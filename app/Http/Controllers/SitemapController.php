<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Journal;
use App\Models\Topic;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $articles = Article::where('status', 'published')->orderBy('updated_at', 'desc')->get();
        $journals = Journal::where('is_active', true)->orderBy('updated_at', 'desc')->get();
        $topics = Topic::where('active', true)->orderBy('updated_at', 'desc')->get();
        $pages = Page::where('active', true)->orderBy('updated_at', 'desc')->get();

        // Check if manual pages exist
        $hasContact = view()->exists('pages.info.contact') || \App\Models\Page::where('slug', 'contact')->exists();
        $hasProcess = view()->exists('pages.info.process');
        $hasProposal = view()->exists('pages.info.proposals');

        return response()->view('sitemap', [
            'articles' => $articles,
            'journals' => $journals,
            'topics' => $topics,
            'pages' => $pages,
            'hasContact' => $hasContact,
            'hasProcess' => $hasProcess,
            'hasProposal' => $hasProposal,
        ])->header('Content-Type', 'text/xml');
    }
}
