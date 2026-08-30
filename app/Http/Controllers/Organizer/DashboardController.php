<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conference;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [
            'total_events' => $user->conferences()->count(),
            'pending_approval' => $user->conferences()->where('status', 'pending')->count(),
            'live_events' => $user->conferences()->where('status', 'approved')->count(),
        ];

        $recentConferences = $user->conferences()->latest()->limit(5)->get();

        return view('organizer.dashboard', compact('stats', 'recentConferences'));
    }
}
