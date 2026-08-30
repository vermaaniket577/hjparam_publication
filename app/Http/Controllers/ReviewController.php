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
            ->with(['submission', 'submission.journal', 'submission.conference'])
            ->orderByRaw('completed_at IS NOT NULL')
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
            ->with(['submission', 'submission.user', 'submission.journal', 'submission.conference'])
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
            return back()->with('error', 'Review already completed.');
        }

        $validated = $request->validate([
            'comments' => 'required|string|min:10',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
            'review_file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip|max:51200',
        ]);

        $updateData = [
            'comments' => $validated['comments'],
            'recommendation' => $validated['recommendation'],
            'completed_at' => now(),
        ];

        if ($request->hasFile('review_file')) {
            $updateData['review_file_path'] = $request->file('review_file')->store('submissions/reviews', 'public');
        }

        $review->update($updateData);

        return redirect()->route('reviews.index')->with('success', 'Review response & evaluation submitted successfully.');
    }
}
