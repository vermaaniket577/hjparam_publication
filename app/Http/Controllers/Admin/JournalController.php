<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        $journals = \App\Models\Journal::paginate(10);
        return view('admin.journals.index', compact('journals'));
    }

    public function create()
    {
        return view('admin.journals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:journals',
            'issn' => 'nullable|string|max:50',
            'format' => 'nullable|string|max:100',
            'frequency' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:150',
            'starting_year' => 'nullable|integer|min:1900|max:2100',
            'publisher' => 'nullable|string|max:255',
            'impact_factor' => 'nullable|numeric',
            'description' => 'nullable|string',
            'aims_and_scope' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        \App\Models\Journal::create($validated);

        return redirect()->route('admin.journals.index')->with('success', 'Journal created successfully.');
    }

    public function edit(\App\Models\Journal $journal)
    {
        return view('admin.journals.edit', compact('journal'));
    }

    public function update(Request $request, \App\Models\Journal $journal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:journals,slug,' . $journal->id,
            'issn' => 'nullable|string|max:50',
            'format' => 'nullable|string|max:100',
            'frequency' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:150',
            'starting_year' => 'nullable|integer|min:1900|max:2100',
            'publisher' => 'nullable|string|max:255',
            'impact_factor' => 'nullable|numeric',
            'description' => 'nullable|string',
            'aims_and_scope' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Handle boolean checkbox (not sent if unchecked)
        $validated['is_active'] = $request->has('is_active');

        $journal->update($validated);

        return redirect()->route('admin.journals.index')->with('success', 'Journal updated successfully.');
    }

    public function destroy(\App\Models\Journal $journal)
    {
        $journal->delete();
        return redirect()->route('admin.journals.index')->with('success', 'Journal deleted successfully.');
    }
}
