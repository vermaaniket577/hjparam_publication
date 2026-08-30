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
            'aim_scope' => 'nullable|string',
            'guidelines' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'nullable|string',
            'city' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'category_id' => 'nullable|exists:topics,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:topics,id',
            'organizer_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'external_link' => 'nullable|url',
            'type' => 'required|in:online,offline,hybrid',
            'banner_image' => 'nullable|image|max:5120',
            'brochure_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:25600',
            'paper_format_1' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
            'paper_format_2' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
            'fee_attendee' => 'nullable|string|max:100',
            'fee_presentation' => 'nullable|string|max:100',
            'fee_publication' => 'nullable|string|max:100',
            'fee_extra_certificate' => 'nullable|string|max:100',
            'custom_fees' => 'nullable|array',
            'custom_fees.*.name' => 'nullable|string|max:255',
            'custom_fees.*.amount' => 'nullable|string|max:100',
            'committee_members' => 'nullable|array',
            'committee_members.*.name' => 'nullable|string|max:255',
            'committee_members.*.role' => 'nullable|string|max:255',
            'committee_members.*.affiliation' => 'nullable|string|max:255',
            'abstract_submission_start_date' => 'nullable|date',
            'abstract_submission_end_date' => 'nullable|date',
            'paper_submission_start_date' => 'nullable|date',
            'paper_submission_end_date' => 'nullable|date',
            'registration_start_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date',
            'early_bird_deadline' => 'nullable|date',
            'invitation_letter_support' => 'nullable|boolean',
            'venue_details' => 'nullable|string',
            'meeting_link' => 'nullable|string|max:500',
            'publication_info' => 'nullable|string',
            'book_publication_title' => 'nullable|string|max:255',
            'book_publication_author' => 'nullable|string|max:255',
            'book_publication_abstract' => 'nullable|string',
            'book_publication_content' => 'nullable|string',
            'journal_publication_title' => 'nullable|string|max:255',
            'journal_publication_content' => 'nullable|string',
            'paper_template' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
            'sample_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:15360',
        ]);

        $categoryIds = $request->input('category_ids', []);
        if (empty($categoryIds) && !empty($validated['category_id'])) {
            $categoryIds = [(int)$validated['category_id']];
        }

        if (empty($categoryIds)) {
            $firstTopic = Topic::first();
            if ($firstTopic) {
                $categoryIds = [$firstTopic->id];
            }
        }

        $validated['category_id'] = $categoryIds[0] ?? null;
        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(1000, 9999);
        $validated['organizer_id'] = auth()->id() ?? 1;
        $validated['status'] = 'approved';
        $validated['venue'] = $validated['venue'] ?? 'Online / Main Convention Center';
        $validated['city'] = $validated['city'] ?? 'Virtual';

        // Filter empty committee members
        if (!empty($validated['committee_members'])) {
            $validated['committee_members'] = array_values(array_filter($validated['committee_members'], function($m) {
                return !empty($m['name']) || !empty($m['role']);
            }));
        }

        // Filter empty custom fees
        if (!empty($validated['custom_fees'])) {
            $validated['custom_fees'] = array_values(array_filter($validated['custom_fees'], function($f) {
                return !empty($f['name']) || !empty($f['amount']);
            }));
        }

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('conferences', 'public');
        }

        if ($request->hasFile('brochure_file')) {
            $validated['brochure_file'] = $request->file('brochure_file')->store('conferences/brochures', 'public');
        }

        if ($request->hasFile('paper_format_1')) {
            $validated['paper_format_1'] = $request->file('paper_format_1')->store('conferences/paper_formats', 'public');
        }

        if ($request->hasFile('paper_format_2')) {
            $validated['paper_format_2'] = $request->file('paper_format_2')->store('conferences/paper_formats', 'public');
        }

        if ($request->hasFile('paper_template')) {
            $validated['paper_template'] = $request->file('paper_template')->store('conferences/templates', 'public');
        }

        if ($request->hasFile('sample_certificate')) {
            $validated['sample_certificate'] = $request->file('sample_certificate')->store('conferences/certificates', 'public');
        }

        unset($validated['category_ids']);
        $conference = Conference::create($validated);
        if (!empty($categoryIds)) {
            $conference->categories()->sync($categoryIds);
        }

        return redirect()->route('admin.conferences.index')->with('success', 'Conference created successfully.');
    }

    public function edit(Conference $conference)
    {
        $conference->load('categories');
        $topics = Topic::all();
        $countries = Country::all();
        return view('admin.conferences.edit', compact('conference', 'topics', 'countries'));
    }

    public function update(Request $request, Conference $conference)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'aim_scope' => 'nullable|string',
            'guidelines' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'nullable|string',
            'city' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'category_id' => 'nullable|exists:topics,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:topics,id',
            'organizer_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'external_link' => 'nullable|url',
            'type' => 'required|in:online,offline,hybrid',
            'banner_image' => 'nullable|image|max:5120',
            'brochure_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:25600',
            'paper_format_1' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
            'paper_format_2' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
            'fee_attendee' => 'nullable|string|max:100',
            'fee_presentation' => 'nullable|string|max:100',
            'fee_publication' => 'nullable|string|max:100',
            'fee_extra_certificate' => 'nullable|string|max:100',
            'custom_fees' => 'nullable|array',
            'custom_fees.*.name' => 'nullable|string|max:255',
            'custom_fees.*.amount' => 'nullable|string|max:100',
            'committee_members' => 'nullable|array',
            'committee_members.*.name' => 'nullable|string|max:255',
            'committee_members.*.role' => 'nullable|string|max:255',
            'committee_members.*.affiliation' => 'nullable|string|max:255',
            'abstract_submission_start_date' => 'nullable|date',
            'abstract_submission_end_date' => 'nullable|date',
            'paper_submission_start_date' => 'nullable|date',
            'paper_submission_end_date' => 'nullable|date',
            'registration_start_date' => 'nullable|date',
            'registration_end_date' => 'nullable|date',
            'early_bird_deadline' => 'nullable|date',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
            'invitation_letter_support' => 'boolean',
            'venue_details' => 'nullable|string',
            'meeting_link' => 'nullable|string|max:500',
            'publication_info' => 'nullable|string',
            'book_publication_title' => 'nullable|string|max:255',
            'book_publication_author' => 'nullable|string|max:255',
            'book_publication_abstract' => 'nullable|string',
            'book_publication_content' => 'nullable|string',
            'journal_publication_title' => 'nullable|string|max:255',
            'journal_publication_content' => 'nullable|string',
            'paper_template' => 'nullable|file|mimes:pdf,doc,docx|max:25600',
            'sample_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:15360',
        ]);

        $categoryIds = $request->input('category_ids', []);
        if (empty($categoryIds) && !empty($validated['category_id'])) {
            $categoryIds = [(int)$validated['category_id']];
        }

        if (empty($categoryIds)) {
            $firstTopic = Topic::first();
            if ($firstTopic) {
                $categoryIds = [$firstTopic->id];
            }
        }

        $validated['category_id'] = $categoryIds[0] ?? null;
        $validated['venue'] = $validated['venue'] ?? 'Online / Main Convention Center';
        $validated['city'] = $validated['city'] ?? 'Virtual';

        // Filter empty committee members
        if (!empty($validated['committee_members'])) {
            $validated['committee_members'] = array_values(array_filter($validated['committee_members'], function($m) {
                return !empty($m['name']) || !empty($m['role']);
            }));
        } else {
            $validated['committee_members'] = [];
        }

        // Filter empty custom fees
        if (!empty($validated['custom_fees'])) {
            $validated['custom_fees'] = array_values(array_filter($validated['custom_fees'], function($f) {
                return !empty($f['name']) || !empty($f['amount']);
            }));
        } else {
            $validated['custom_fees'] = [];
        }

        if ($request->hasFile('banner_image')) {
            if ($conference->banner_image) {
                Storage::disk('public')->delete($conference->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('conferences', 'public');
        }

        if ($request->hasFile('brochure_file')) {
            if ($conference->brochure_file) {
                Storage::disk('public')->delete($conference->brochure_file);
            }
            $validated['brochure_file'] = $request->file('brochure_file')->store('conferences/brochures', 'public');
        }

        if ($request->hasFile('paper_format_1')) {
            if ($conference->paper_format_1) {
                Storage::disk('public')->delete($conference->paper_format_1);
            }
            $validated['paper_format_1'] = $request->file('paper_format_1')->store('conferences/paper_formats', 'public');
        }

        if ($request->hasFile('paper_format_2')) {
            if ($conference->paper_format_2) {
                Storage::disk('public')->delete($conference->paper_format_2);
            }
            $validated['paper_format_2'] = $request->file('paper_format_2')->store('conferences/paper_formats', 'public');
        }

        if ($request->hasFile('paper_template')) {
            if ($conference->paper_template) {
                Storage::disk('public')->delete($conference->paper_template);
            }
            $validated['paper_template'] = $request->file('paper_template')->store('conferences/templates', 'public');
        }

        if ($request->hasFile('sample_certificate')) {
            if ($conference->sample_certificate) {
                Storage::disk('public')->delete($conference->sample_certificate);
            }
            $validated['sample_certificate'] = $request->file('sample_certificate')->store('conferences/certificates', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . $conference->id;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['invitation_letter_support'] = $request->has('invitation_letter_support');

        unset($validated['category_ids']);
        $conference->update($validated);
        if (!empty($categoryIds)) {
            $conference->categories()->sync($categoryIds);
        }

        return redirect()->route('admin.conferences.index')->with('success', 'Conference updated successfully.');
    }

    public function destroy(Conference $conference)
    {
        if ($conference->banner_image) {
            Storage::disk('public')->delete($conference->banner_image);
        }
        if ($conference->brochure_file) {
            Storage::disk('public')->delete($conference->brochure_file);
        }
        if ($conference->paper_format_1) {
            Storage::disk('public')->delete($conference->paper_format_1);
        }
        if ($conference->paper_format_2) {
            Storage::disk('public')->delete($conference->paper_format_2);
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
