<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\Volume;
use App\Models\Issue;
use Illuminate\Http\Request;

class VolumeController extends Controller
{
    /**
     * Display all volumes and issues for a specific journal.
     */
    public function index(Journal $journal)
    {
        $journal->load(['volumes' => function ($q) {
            $q->orderBy('year', 'desc')
              ->orderBy('volume_number', 'desc')
              ->with(['issues' => function ($iq) {
                  $iq->orderBy('issue_number', 'asc')->withCount('articles');
              }]);
        }]);

        return view('admin.journals.volumes.index', compact('journal'));
    }

    /**
     * Create a new volume for this journal.
     */
    public function store(Request $request, Journal $journal)
    {
        $validated = $request->validate([
            'volume_number' => 'required|string|max:50',
            'month' => 'nullable|string|max:50',
            'year' => 'required|integer|min:1900|max:2100',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['journal_id'] = $journal->id;

        Volume::create($validated);

        $monthYear = trim(($validated['month'] ?? '') . ' ' . $validated['year']);
        return back()->with('success', "Volume {$validated['volume_number']} ({$monthYear}) created successfully.");
    }

    /**
     * Delete a volume.
     */
    public function destroy(Journal $journal, Volume $volume)
    {
        $volumeNumber = $volume->volume_number;
        $volume->delete();

        return back()->with('success', "Volume {$volumeNumber} and its issues have been deleted.");
    }

    /**
     * Create a new issue under a volume.
     */
    public function storeIssue(Request $request, Volume $volume)
    {
        $validated = $request->validate([
            'issue_number' => 'required|string|max:50',
            'publication_date' => 'nullable|date',
            'special_issue_title' => 'nullable|string|max:255',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['volume_id'] = $volume->id;

        Issue::create($validated);

        return back()->with('success', "Issue {$validated['issue_number']} added to Volume {$volume->volume_number}.");
    }

    /**
     * Delete an issue.
     */
    public function destroyIssue(Volume $volume, Issue $issue)
    {
        $issueNumber = $issue->issue_number;
        $issue->delete();

        return back()->with('success', "Issue {$issueNumber} has been deleted.");
    }
}
