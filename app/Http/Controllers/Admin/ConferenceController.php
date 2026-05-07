<?php

namespace App\Http\Controllers\Admin;

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
        $conferences = Conference::with(['country', 'category', 'organizer'])
            ->latest()
            ->paginate(20);
            
        return view('admin.conferences.index', compact('conferences'));
    }

    public function create()
    {
        $topics = Topic::all();
        $countries = Country::all();
        return view('admin.conferences.create', compact('topics', 'countries'));
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
            'early_bird_deadline' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(1000, 9999);
        $validated['organizer_id'] = auth()->id();
        $validated['status'] = 'approved'; // Admin directly creates approved ones

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('conferences', 'public');
        }

        Conference::create($validated);

        return redirect()->route('admin.conferences.index')->with('success', 'Conference created successfully.');
    }

    public function edit(Conference $conference)
    {
        $topics = Topic::all();
        $countries = Country::all();
        return view('admin.conferences.edit', compact('conference', 'topics', 'countries'));
    }

    public function update(Request $request, Conference $conference)
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
            'early_bird_deadline' => 'nullable|date',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
            'invitation_letter_support' => 'boolean',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($conference->banner_image) {
                Storage::disk('public')->delete($conference->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('conferences', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . $conference->id;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['invitation_letter_support'] = $request->has('invitation_letter_support');

        $conference->update($validated);

        return redirect()->route('admin.conferences.index')->with('success', 'Conference updated successfully.');
    }

    public function destroy(Conference $conference)
    {
        if ($conference->banner_image) {
            Storage::disk('public')->delete($conference->banner_image);
        }
        $conference->delete();

        return redirect()->route('admin.conferences.index')->with('success', 'Conference deleted successfully.');
    }

    public function toggleFeatured(Conference $conference)
    {
        $conference->is_featured = !$conference->is_featured;
        $conference->save();
        
        return back()->with('success', 'Featured status updated.');
    }
}
