<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\Volume;
use App\Models\Issue;
use App\Models\Article;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::where('is_active', true);

        if ($request->has('letter')) {
            $letter = $request->get('letter');
            $query->where('title', 'LIKE', $letter . '%');
        }

        $journals = $query->orderBy('title')->get();
        return view('journals.index', compact('journals'));
    }

    public function show($slug)
    {
        $journal = Journal::where('slug', $slug)->firstOrFail();

        // Load relationships needed for the view
        $volumes = $journal->volumes()->orderBy('year', 'desc')->with('issues')->get();
        $board = $journal->editorialBoard()->orderBy('role')->get();

        // Get latest issue articles
        $latestIssue = $journal->issues()->latest('publication_date')->first();
        $articles = $latestIssue ? $latestIssue->articles()->with('authors')->where('status', 'published')->get() : collect();

        return view('journals.show', compact('journal', 'volumes', 'board', 'articles', 'latestIssue'));
    }

    public function issue($slug, $volumeNum, $issueNum)
    {
        $journal = Journal::where('slug', $slug)->firstOrFail();

        $volume = $journal->volumes()
            ->where('volume_number', $volumeNum)
            ->firstOrFail();

        $issue = $volume->issues()
            ->where('issue_number', $issueNum)
            ->firstOrFail();

        $articles = $issue->articles()
            ->with('authors')
            ->where('status', 'published')
            ->get();

        return view('journals.issue', compact('journal', 'volume', 'issue', 'articles'));
    }

    public function subjects()
    {
        $journals = Journal::where('is_active', true)->with('topic')->get();
        return view('journals.subjects', compact('journals'));
    }
}
