<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Article;
use App\Models\Partner;
use App\Models\News;
use App\Models\Conference;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJournals = Journal::where('is_active', true)->orderBy('id', 'asc')->get();
        $latestArticles = Article::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->with(['journal', 'authors'])
            ->get();

        $partners = Partner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $latestNews = News::where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $today = now()->startOfDay();

        // Fetch upcoming & ongoing approved conferences (lapsed conferences are automatically excluded)
        $upcomingConferences = Conference::where('status', 'approved')
            ->where(function ($query) use ($today) {
                $query->where(function ($q) use ($today) {
                    $q->whereNotNull('end_date')
                      ->where('end_date', '>=', $today);
                })->orWhere(function ($q) use ($today) {
                    $q->whereNull('end_date')
                      ->where('start_date', '>=', $today);
                });
            })
            ->with(['country', 'category', 'categories'])
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        return view('home', compact('featuredJournals', 'latestArticles', 'partners', 'latestNews', 'upcomingConferences'));
    }
}
