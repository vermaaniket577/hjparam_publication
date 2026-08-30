<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Topic;

class SubscriptionController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return view('subscribe', compact('topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscriptions,email',
            'interests' => 'nullable|array',
            'interests.*' => 'exists:topics,id',
        ]);

        Subscription::create([
            'email' => $validated['email'],
            'interests' => $validated['interests'] ?? [],
            'active' => true,
        ]);

        return back()->with('success', 'You have successfully subscribed to HJPARAM Academic Alerts.');
    }
}
