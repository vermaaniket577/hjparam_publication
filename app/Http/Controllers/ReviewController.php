<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::where('reviewer_id', Auth::id())
            ->with(['submission', 'submission.journal'])
            ->orderByRaw('completed_at IS NOT NULL') // Pending first
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $review = Review::where('reviewer_id', Auth::id())
            ->where('id', $id)
            ->with(['submission', 'submission.user', 'submission.journal'])
            ->firstOrFail();

        return view('reviews.show', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('reviewer_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($review->completed_at) {
            return back()->with('error', 'Review already submitted.');
        }

        $validated = $request->validate([
            'comments' => 'required|string|min:20',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
        ]);

        $review->update([
            'comments' => $validated['comments'],
            'recommendation' => $validated['recommendation'],
            'completed_at' => now(),
        ]);

        // Optionally update submission status based on workflow logic, 
        // e.g. move to 'reviewed' if all reviews are in. 
        // For now, we leave it to the editor to update submission status manually.

        return redirect()->route('reviews.index')->with('success', 'Review submitted successfully.');
    }
}
