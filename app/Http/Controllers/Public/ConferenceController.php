<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Country;
use App\Models\Topic;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Conference::query()->where('status', 'approved');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('country')) {
            $query->whereHas('country', function($q) use ($request) {
                $q->where('slug', $request->country);
            });
        }

        if ($request->has('topic')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->topic);
            });
        }

        $conferences = $query->orderBy('start_date')->paginate(12);
        
        $countries = Country::orderBy('name')->get();
        $topics = Topic::where('active', true)->get();

        return view('conferences.index', compact('conferences', 'countries', 'topics'));
    }

    public function show($slug)
    {
        $conference = Conference::where('slug', $slug)->firstOrFail();
        return view('conferences.show', compact('conference'));
    }

    public function category($slug)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();
        $conferences = Conference::where('category_id', $topic->id)
            ->where('status', 'approved')
            ->orderBy('start_date')
            ->paginate(12);
        
        $countries = Country::orderBy('name')->get();
        $topics = Topic::where('active', true)->get();

        return view('conferences.index', compact('conferences', 'countries', 'topics', 'topic'));
    }

    public function search(Request $request)
    {
        // AJAX Search for live filtering
        $query = Conference::query()->where('status', 'approved');

        if ($request->q) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $conferences = $query->take(10)->get(['title', 'slug', 'city']);

        return response()->json($conferences);
    }
}
