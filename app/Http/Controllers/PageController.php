<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $request)
    {
        $category = $request->route('category');
        $slug = $request->route('slug');

        // Special handling for topics index
        if ($category === 'topics' && !$slug) {
            return view('pages.topics.index');
        }

        // Special handling for topics show
        if ($category === 'topics' && $slug) {
            $topic = \App\Models\Topic::where('slug', $slug)->where('active', true)->first();
            if ($topic) {
                return view('pages.topics.show', compact('topic'));
            }
        }

        // Special handling for news index
        if ($category === 'info' && $slug === 'news') {
            $news = \App\Models\News::where('is_active', true)->orderBy('published_at', 'desc')->get();
            return view('pages.info.news', compact('news'));
        }

        // 1. Check for specific static view first (Highest priority/design)
        $viewName = "pages.{$category}" . ($slug ? ".{$slug}" : ".index");
        if (view()->exists($viewName)) {
            return view($viewName);
        }

        // 1b. Check for dynamic news if category is info
        if ($category === 'info' && $slug) {
            $news = \App\Models\News::where('slug', $slug)->where('is_active', true)->first();
            if ($news) {
                return view('pages.info.news-dynamic', compact('news'));
            }
        }

        // 2. Fallback to generic database page
        $page = \App\Models\Page::where('category', $category)
            ->where('slug', $slug)
            ->where('active', true)
            ->first();

        if ($page) {
            return view('pages.generic', [
                'title' => $page->title,
                'content' => $page->content,
            ]);
        }

        abort(404);
    }

    public function submit()
    {
        return view('pages.author.submit');
    }

    public function guidelines()
    {
        return view('pages.author.guidelines');
    }
}
