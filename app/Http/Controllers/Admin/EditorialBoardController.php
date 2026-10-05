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
        $this->ensureSchemaExists();

        $query = $journal->editorialBoard();
        if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
            $query->orderBy('sort_order', 'asc');
        }

        $members = $query->orderBy('role', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $roles = self::ROLES;

        return view('admin.journals.editorial.index', compact('journal', 'members', 'roles'));
    }

    public function store(Request $request, Journal $journal)
    {
        $this->ensureSchemaExists();

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

        if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
            $validated['sort_order'] = $validated['sort_order'] ?? 0;
        } else {
            unset($validated['sort_order']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
            unset($validated['email']);
        }

        $journal->editorialBoard()->create($validated);

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial team member added successfully.');
    }

    public function update(Request $request, Journal $journal, EditorialBoard $member)
    {
        $this->ensureSchemaExists();

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

        if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
            $validated['sort_order'] = $validated['sort_order'] ?? 0;
        } else {
            unset($validated['sort_order']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
            unset($validated['email']);
        }

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
        $this->ensureSchemaExists();

        $validated = $request->validate([
            'editorial_responsibilities' => 'nullable|string',
        ]);

        $journal->update($validated);

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial responsibilities policy updated successfully.');
    }

    public static function ensureSchemaExists(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
                \Illuminate\Support\Facades\Schema::table('editorial_boards', function ($table) {
                    $table->integer('sort_order')->default(0)->after('photo');
                });
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
                \Illuminate\Support\Facades\Schema::table('editorial_boards', function ($table) {
                    $table->string('email')->nullable()->after('affiliation');
                });
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('journals', 'editorial_responsibilities')) {
                \Illuminate\Support\Facades\Schema::table('journals', function ($table) {
                    $table->longText('editorial_responsibilities')->nullable()->after('aims_and_scope');
                });
            }
        } catch (\Throwable $e) {
            // Silently fallback if table operation already performed or restricted
        }
    }
}
