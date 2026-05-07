<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Article;
use App\Models\Partner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJournals = Journal::where('is_active', true)->take(4)->get();
        $latestArticles = Article::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->with(['journal', 'authors'])
            ->get();

        $partners = Partner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $latestNews = \App\Models\News::where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $featuredConferences = \App\Models\Conference::where('is_featured', true)
            ->where('status', 'approved')
            ->take(4)
            ->get();

        $latestConferences = \App\Models\Conference::where('status', 'approved')
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        return view('home', compact('featuredJournals', 'latestArticles', 'partners', 'latestNews', 'featuredConferences', 'latestConferences'));
    }
}
