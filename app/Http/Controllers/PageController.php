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

        // Special handling for dynamic Editorial Board page
        if ($category === 'about' && $slug === 'editorial-board') {
            \App\Http\Controllers\Admin\EditorialBoardController::ensureSchemaExists();

            $page = \App\Models\Page::where('category', 'about')
                ->where('slug', 'editorial-board')
                ->first();

            $badge = \App\Models\Setting::get('editorial_board_badge', 'Leadership');
            $pageTitle = \App\Models\Setting::get('editorial_board_title', $page?->title ?? 'Editorial Board');
            $pageSubtitle = \App\Models\Setting::get('editorial_board_subtitle', 'Our board consists of world-renowned scholars and researchers dedicated to upholding the highest standards of academic excellence.');
            $joinTitle = \App\Models\Setting::get('editorial_board_join_title', 'Join Our Editorial Board');
            $joinText = \App\Models\Setting::get('editorial_board_join_text', 'We are always looking for distinguished scholars to join our editorial team. If you are interested in becoming a section editor or reviewer, please contact us.');
            $joinUrl = \App\Models\Setting::get('editorial_board_join_url', route('about.page', 'contact-information'));

            $journals = \App\Models\Journal::where('is_active', true)->orderBy('title')->get();
            $selectedJournalId = $request->query('journal');

            $query = \App\Models\EditorialBoard::with('journal');
            if ($selectedJournalId) {
                if ($selectedJournalId === 'global') {
                    $query->whereNull('journal_id');
                } else {
                    $query->where('journal_id', $selectedJournalId);
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
                $query->orderBy('sort_order', 'asc');
            }

            $members = $query->orderBy('name', 'asc')->get();

            // Group members into Leadership/Chief, Section Editors, and General/Advisory Board
            $chiefEditors = $members->filter(fn($m) => stripos($m->role, 'Chief') !== false);
            $sectionEditors = $members->filter(fn($m) => stripos($m->role, 'Section') !== false);
            $otherMembers = $members->reject(fn($m) => stripos($m->role, 'Chief') !== false || stripos($m->role, 'Section') !== false);

            return view('pages.about.editorial-board', compact(
                'page', 'badge', 'pageTitle', 'pageSubtitle', 'joinTitle', 'joinText', 'joinUrl',
                'journals', 'selectedJournalId', 'members', 'chiefEditors', 'sectionEditors', 'otherMembers'
            ));
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
