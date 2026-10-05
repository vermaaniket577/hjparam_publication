<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Models\EditorialBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorialBoardController extends Controller
{
    public const ROLES = [
        'Editor-in-Chief',
        'Associate Editor',
        'Managing Editor',
        'Executive Editor',
        'Section Editor',
        'Editorial Board Member',
        'Advisory Board Member',
        'Review Editor',
    ];

    public function index(Journal $journal)
    {
        $members = $journal->editorialBoard()
            ->orderBy('sort_order', 'asc')
            ->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $roles = self::ROLES;

        return view('admin.journals.editorial.index', compact('journal', 'members', 'roles'));
    }

    public function store(Request $request, Journal $journal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'affiliation' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('editorial', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $journal->editorialBoard()->create($validated);

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial team member added successfully.');
    }

    public function update(Request $request, Journal $journal, EditorialBoard $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'affiliation' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('editorial', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $member->update($validated);

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial team member updated successfully.');
    }

    public function destroy(Journal $journal, EditorialBoard $member)
    {
        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial team member removed successfully.');
    }

    public function updateResponsibilities(Request $request, Journal $journal)
    {
        $validated = $request->validate([
            'editorial_responsibilities' => 'nullable|string',
        ]);

        $journal->update($validated);

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial responsibilities policy updated successfully.');
    }
}
