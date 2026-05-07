<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\Topic;
use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ConferenceController extends Controller
{
    public function index()
    {
        $conferences = auth()->user()->conferences()
            ->with(['country', 'category'])
            ->latest()
            ->paginate(15);
            
        return view('organizer.conferences.index', compact('conferences'));
    }

    public function create()
    {
        $topics = Topic::all();
        $countries = Country::all();
        return view('organizer.conferences.create', compact('topics', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'required|string',
            'city' => 'required|string',
            'country_id' => 'required|exists:countries,id',
            'category_id' => 'required|exists:topics,id',
            'organizer_name' => 'required|string',
            'external_link' => 'nullable|url',
            'type' => 'required|in:online,offline,hybrid',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(1000, 9999);
        $validated['organizer_id'] = auth()->id();
        $validated['status'] = 'pending'; // Organizers create pending ones

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('conferences', 'public');
        }

        Conference::create($validated);

        return redirect()->route('organizer.conferences.index')->with('success', 'Your conference has been submitted and is awaiting administrator approval.');
    }

    public function edit(Conference $conference)
    {
        // Policy Check
        if ($conference->organizer_id !== auth()->id()) {
            abort(403);
        }
        
        $topics = Topic::all();
        $countries = Country::all();
        return view('organizer.conferences.edit', compact('conference', 'topics', 'countries'));
    }

    public function update(Request $request, Conference $conference)
    {
        if ($conference->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'required|string',
            'city' => 'required|string',
            'country_id' => 'required|exists:countries,id',
            'category_id' => 'required|exists:topics,id',
            'organizer_name' => 'required|string',
            'external_link' => 'nullable|url',
            'type' => 'required|in:online,offline,hybrid',
            'banner_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($conference->banner_image) {
                Storage::disk('public')->delete($conference->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('conferences', 'public');
        }

        // If title changed, keep the previous slug or update it?
        // Let's keep it consistent.
        
        $conference->update($validated);

        return redirect()->route('organizer.conferences.index')->with('success', 'Conference details updated.');
    }

    public function destroy(Conference $conference)
    {
        if ($conference->organizer_id !== auth()->id()) {
            abort(403);
        }

        if ($conference->banner_image) {
            Storage::disk('public')->delete($conference->banner_image);
        }
        $conference->delete();

        return redirect()->route('organizer.conferences.index')->with('success', 'Event registry has been removed.');
    }
}
