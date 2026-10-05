<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        \App\Http\Controllers\Admin\EditorialBoardController::ensureSchemaExists();
        $journals = \App\Models\Journal::where('is_active', true)->orderBy('title')->get();
        $editorialRoles = \App\Http\Controllers\Admin\EditorialBoardController::ROLES;
        return view('admin.users.create', compact('journals', 'editorialRoles'));
    }

    public function store(Request $request)
    {
        \App\Http\Controllers\Admin\EditorialBoardController::ensureSchemaExists();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,editor,reviewer,author,sub_admin,super_admin',
            'affiliation' => 'nullable|string|max:255',
            'journal_id' => 'nullable|exists:journals,id',
            'editorial_role' => 'nullable|string|max:100',
            'editorial_bio' => 'nullable|string',
            'editorial_sort_order' => 'nullable|integer|min:0',
        ]);

        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'affiliation' => $validated['affiliation'] ?? null,
        ]);

        // Automatically assign to Journal Editorial Team if journal is selected
        if ($request->filled('journal_id')) {
            $journal = \App\Models\Journal::find($request->journal_id);
            if ($journal) {
                $boardData = [
                    'journal_id' => $journal->id,
                    'name' => $user->name,
                    'affiliation' => $user->affiliation ?: ($journal->title . ' Editorial Office'),
                    'email' => $user->email,
                    'role' => $request->editorial_role ?: 'Editorial Board Member',
                    'bio' => $request->editorial_bio ?: null,
                ];

                if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
                    $boardData['sort_order'] = $request->editorial_sort_order ?? 0;
                }

                if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
                    unset($boardData['email']);
                }

                $existing = \App\Models\EditorialBoard::where('email', $user->email)->first();
                if ($existing) {
                    $existing->update($boardData);
                } else {
                    $journal->editorialBoard()->create($boardData);
                }
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully' . ($request->filled('journal_id') ? ' and appointed to journal editorial team.' : '.'));
    }

    public function edit(\App\Models\User $user)
    {
        \App\Http\Controllers\Admin\EditorialBoardController::ensureSchemaExists();
        $journals = \App\Models\Journal::where('is_active', true)->orderBy('title')->get();
        $editorialRoles = \App\Http\Controllers\Admin\EditorialBoardController::ROLES;
        $currentBoardMembership = \App\Models\EditorialBoard::where('email', $user->email)->first();

        return view('admin.users.edit', compact('user', 'journals', 'editorialRoles', 'currentBoardMembership'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        \App\Http\Controllers\Admin\EditorialBoardController::ensureSchemaExists();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,editor,reviewer,author,sub_admin,super_admin',
            'affiliation' => 'nullable|string|max:255',
            'journal_id' => 'nullable|exists:journals,id',
            'editorial_role' => 'nullable|string|max:100',
            'editorial_bio' => 'nullable|string',
            'editorial_sort_order' => 'nullable|integer|min:0',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'affiliation' => $validated['affiliation'] ?? null,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        // Update or assign Journal Editorial Team membership
        if ($request->filled('journal_id')) {
            $journal = \App\Models\Journal::find($request->journal_id);
            if ($journal) {
                $boardData = [
                    'journal_id' => $journal->id,
                    'name' => $user->name,
                    'affiliation' => $user->affiliation ?: ($journal->title . ' Editorial Office'),
                    'email' => $user->email,
                    'role' => $request->editorial_role ?: 'Editorial Board Member',
                    'bio' => $request->editorial_bio ?: null,
                ];

                if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
                    $boardData['sort_order'] = $request->editorial_sort_order ?? 0;
                }

                if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
                    unset($boardData['email']);
                }

                $existing = \App\Models\EditorialBoard::where('email', $user->email)->first();
                if ($existing) {
                    $existing->update($boardData);
                } else {
                    \App\Models\EditorialBoard::create($boardData);
                }
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function acceptReviewer(\App\Models\User $user)
    {
        $user->update(['role' => 'reviewer']);
        return back()->with('success', "User {$user->name} has been approved as a Reviewer.");
    }

    public function rejectReviewer(\App\Models\User $user)
    {
        $user->update(['role' => 'author']);
        return back()->with('success', "Reviewer status for {$user->name} was revoked.");
    }
}
