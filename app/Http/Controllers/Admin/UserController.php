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

        // Automatically assign to Journal Editorial Team(s) with multiple roles journal-wise
        $assignments = [];
        if ($request->filled('journal_roles_json')) {
            $decoded = json_decode($request->journal_roles_json, true);
            if (is_array($decoded)) {
                $assignments = $decoded;
            }
        } elseif ($request->has('journal_roles') && is_array($request->journal_roles)) {
            $assignments = $request->journal_roles;
        } elseif ($request->filled('journal_id')) {
            $roles = $request->editorial_roles ?: ($request->editorial_role ? [$request->editorial_role] : ['Editorial Board Member']);
            $assignments[(string) $request->journal_id] = (array) $roles;
        }

        if (!empty($assignments)) {
            $editorUid = 'ed_' . str_replace('-', '', (string) \Illuminate\Support\Str::uuid());
            foreach ($assignments as $jKey => $rolesList) {
                $journalId = ($jKey === 'global' || empty($jKey)) ? null : (int) $jKey;
                $rolesList = is_array($rolesList)
                    ? array_values(array_filter(array_map('trim', $rolesList)))
                    : array_values(array_filter(array_map('trim', explode(',', (string) $rolesList))));
                $roleString = implode(', ', $rolesList) ?: 'Editorial Board Member';

                $journalTitle = 'Publisher-wide';
                if ($journalId) {
                    $jObj = \App\Models\Journal::find($journalId);
                    if ($jObj) $journalTitle = $jObj->title;
                }

                \App\Models\EditorialBoard::create([
                    'editor_uid' => $editorUid,
                    'journal_id' => $journalId,
                    'name' => $user->name,
                    'affiliation' => $user->affiliation ?: ($journalTitle . ' Editorial Office'),
                    'email' => $user->email,
                    'role' => $roleString,
                    'bio' => $request->editorial_bio ?: null,
                    'sort_order' => $request->editorial_sort_order ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully' . (!empty($assignments) ? ' and appointed to journal editorial team(s).' : '.'));
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

        // Update or assign Journal Editorial Team membership(s) with multiple roles journal-wise
        $assignments = [];
        if ($request->filled('journal_roles_json')) {
            $decoded = json_decode($request->journal_roles_json, true);
            if (is_array($decoded)) {
                $assignments = $decoded;
            }
        } elseif ($request->has('journal_roles') && is_array($request->journal_roles)) {
            $assignments = $request->journal_roles;
        } elseif ($request->filled('journal_id')) {
            $roles = $request->editorial_roles ?: ($request->editorial_role ? [$request->editorial_role] : ['Editorial Board Member']);
            $assignments[(string) $request->journal_id] = (array) $roles;
        }

        if (!empty($assignments)) {
            $existingRecords = \App\Models\EditorialBoard::where('email', $user->email)->get();
            $existingByJournal = [];
            $editorUid = null;
            foreach ($existingRecords as $rec) {
                $k = $rec->journal_id ? (string) $rec->journal_id : 'global';
                $existingByJournal[$k] = $rec;
                if (!$editorUid && $rec->editor_uid) {
                    $editorUid = $rec->editor_uid;
                }
            }
            if (!$editorUid) {
                $editorUid = 'ed_' . str_replace('-', '', (string) \Illuminate\Support\Str::uuid());
            }

            foreach ($assignments as $jKey => $rolesList) {
                $journalId = ($jKey === 'global' || empty($jKey)) ? null : (int) $jKey;
                $rolesList = is_array($rolesList)
                    ? array_values(array_filter(array_map('trim', $rolesList)))
                    : array_values(array_filter(array_map('trim', explode(',', (string) $rolesList))));
                $roleString = implode(', ', $rolesList) ?: 'Editorial Board Member';

                $journalTitle = 'Publisher-wide';
                if ($journalId) {
                    $jObj = \App\Models\Journal::find($journalId);
                    if ($jObj) $journalTitle = $jObj->title;
                }

                $boardData = [
                    'editor_uid' => $editorUid,
                    'journal_id' => $journalId,
                    'name' => $user->name,
                    'affiliation' => $user->affiliation ?: ($journalTitle . ' Editorial Office'),
                    'email' => $user->email,
                    'role' => $roleString,
                    'bio' => $request->editorial_bio ?: null,
                    'sort_order' => $request->editorial_sort_order ?? 0,
                ];

                if (isset($existingByJournal[$jKey])) {
                    $existingByJournal[$jKey]->update($boardData);
                    unset($existingByJournal[$jKey]);
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
