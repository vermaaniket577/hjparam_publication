<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::latest()->paginate(20);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $subscription->update([
            'active' => !$subscription->active,
        ]);

        return back()->with('success', 'Subscription status toggled.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return back()->with('success', 'Subscriber removed.');
    }
}
