<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Journal;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // 1. Overview Stats
        $stats = [
            'total_users' => User::count(),
            'total_submissions' => Submission::count(),
            'published_articles' => Article::where('status', 'published')->count(),
            'pending_reviews' => Submission::where('status', 'under_review')->count(),
        ];

        // 2. Submissions by Month (Last 6 months)
        $submissionsByMonth = Submission::select(
            DB::raw('count(id) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_name")
        )
            ->groupBy('month_name')
            ->orderBy('month_name', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        // 3. Articles by Journal
        $articlesByJournal = Journal::withCount([
            'articles' => function ($query) {
                $query->where('status', 'published');
            }
        ])
            ->orderBy('articles_count', 'desc')
            ->take(5)
            ->get();

        // 4. Most Viewed Articles
        $topArticles = Article::orderBy('views_count', 'desc')
            ->take(5)
            ->with('journal')
            ->get();

        return view('admin.analytics.index', compact('stats', 'submissionsByMonth', 'articlesByJournal', 'topArticles'));
    }
}
