<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Setting;
use Illuminate\Http\Response;

class RSSFeedController extends Controller
{
    public function index()
    {
        // Fetch latest published articles
        $articles = Article::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(30)
            ->with(['journal', 'authors'])
            ->get();

        $siteTitle = Setting::where('key', 'site_name')->value('value') ?? config('app.name', 'HJPARAM Publication');
        $siteDescription = Setting::where('key', 'site_description')->value('value') ?? 'Latest scholarly research and open access publications from HJPARAM.';
        $feedUrl = route('rss.feed');
        $siteUrl = url('/');

        $buildDate = ($articles->first() && $articles->first()->published_at) 
            ? $articles->first()->published_at->toRssString() 
            : now()->toRssString();

        $content = view('rss.feed', compact('articles', 'siteTitle', 'siteDescription', 'feedUrl', 'siteUrl', 'buildDate'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
