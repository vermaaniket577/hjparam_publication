<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'journals' => \App\Models\Journal::count(),
            'users' => \App\Models\User::count(),
            'submissions_total' => \App\Models\Submission::count(),
            'articles_published' => \App\Models\Article::where('status', 'published')->count(),
            'submissions_pending' => \App\Models\Submission::where('status', 'submitted')->count(),
            'total_conferences' => \App\Models\Conference::count(),
            'pending_conferences' => \App\Models\Conference::where('status', 'pending')->count(),
            'total_organizers' => \App\Models\User::where('role', 'organizer')->count(),
            'total_subscribers' => \App\Models\Subscription::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
