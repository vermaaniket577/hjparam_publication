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

        // Support multiple roles from checkbox dropdown or plain string
        $rolesInput = $request->input('roles');
        if (is_array($rolesInput) && count($rolesInput) > 0) {
            $roleString = implode(', ', array_values(array_filter(array_map('trim', $rolesInput))));
        } else {
            $roleString = trim((string) $request->input('role', ''));
        }
        $request->merge(['role' => $roleString]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'affiliation' => 'required|string|max:255',
            'postal_address' => 'nullable|string|max:1000',
            'country' => 'nullable|string|max:100',
            'is_international' => 'nullable|boolean',
            'role' => 'required|string|max:500',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $validated['is_international'] = $request->boolean('is_international');

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

        // Support multiple roles from checkbox dropdown or plain string
        $rolesInput = $request->input('roles');
        if (is_array($rolesInput) && count($rolesInput) > 0) {
            $roleString = implode(', ', array_values(array_filter(array_map('trim', $rolesInput))));
        } else {
            $roleString = trim((string) $request->input('role', ''));
        }
        $request->merge(['role' => $roleString]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'affiliation' => 'required|string|max:255',
            'postal_address' => 'nullable|string|max:1000',
            'country' => 'nullable|string|max:100',
            'is_international' => 'nullable|boolean',
            'role' => 'required|string|max:500',
            'email' => 'nullable|email|max:255',
            'bio' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $validated['is_international'] = $request->boolean('is_international');

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
        self::ensureSchemaExists();

        $validated = $request->validate([
            'editorial_responsibilities' => 'nullable|string',
        ]);

        $journal->update($validated);

        return redirect()->route('admin.journals.editorial.index', $journal)
            ->with('success', 'Editorial responsibilities policy updated successfully.');
    }

    /**
     * Manage all Editorial Board members across all journals / central board.
     */
    public function manageAll(Request $request)
    {
        self::ensureSchemaExists();

        $journals = Journal::where('is_active', true)->orderBy('title')->get();
        $roles = self::ROLES;

        $query = EditorialBoard::with('journal');

        if ($request->filled('journal_id')) {
            if ($request->journal_id === 'global') {
                $query->whereNull('journal_id');
            } else {
                $query->where('journal_id', $request->journal_id);
            }
        }

        if ($request->filled('role')) {
            $query->where('role', 'like', "%{$request->role}%");
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('affiliation', 'like', "%{$s}%")
                    ->orWhere('bio', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
            $query->orderBy('sort_order', 'asc');
        }

        $members = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        $allMembers = EditorialBoard::all();
        $internationalCount = $allMembers->filter(fn($m) => (bool)$m->is_international)->count();
        $postalCount = $allMembers->filter(fn($m) => !empty(trim((string)$m->postal_address)))->count();
        $personalEmailCount = $allMembers->filter(fn($m) => !empty($m->email) && !$m->is_institutional_email)->count();

        $stats = [
            'total' => $allMembers->count(),
            'chief' => $allMembers->filter(fn($m) => stripos($m->role, 'Chief') !== false)->count(),
            'section' => $allMembers->filter(fn($m) => stripos($m->role, 'Section') !== false)->count(),
            'other' => $allMembers->filter(fn($m) => stripos($m->role, 'Chief') === false && stripos($m->role, 'Section') === false)->count(),
            'international' => $internationalCount,
            'with_postal' => $postalCount,
            'personal_emails' => $personalEmailCount,
            'issn_compliant' => ($allMembers->count() >= 5) && ($internationalCount >= 2) && ($postalCount >= 5) && ($personalEmailCount === 0),
        ];

        $pageSettings = [
            'badge' => \App\Models\Setting::get('editorial_board_badge', 'Leadership'),
            'title' => \App\Models\Setting::get('editorial_board_title', 'Editorial Board'),
            'subtitle' => \App\Models\Setting::get('editorial_board_subtitle', 'Our board consists of world-renowned scholars and researchers dedicated to upholding the highest standards of academic excellence.'),
            'join_title' => \App\Models\Setting::get('editorial_board_join_title', 'Join Our Editorial Board'),
            'join_text' => \App\Models\Setting::get('editorial_board_join_text', 'We are always looking for distinguished scholars to join our editorial team. If you are interested in becoming a section editor or reviewer, please contact us.'),
            'join_url' => \App\Models\Setting::get('editorial_board_join_url', route('about.page', 'contact-information')),
        ];

        return view('admin.editorial.index', compact('members', 'journals', 'roles', 'stats', 'pageSettings'));
    }

    /**
     * Helper to parse journal-wise role assignments from request.
     * Returns array like ['global' => ['Editor-in-Chief', 'Managing Editor'], '1' => ['Associate Editor']]
     */
    protected function parseJournalAssignments(Request $request): array
    {
        $assignments = [];

        // 1. Try JSON input (journal_roles_json)
        if ($request->filled('journal_roles_json')) {
            $decoded = json_decode($request->journal_roles_json, true);
            if (is_array($decoded)) {
                foreach ($decoded as $jKey => $roles) {
                    $rolesList = is_array($roles)
                        ? array_values(array_filter(array_map('trim', $roles)))
                        : array_values(array_filter(array_map('trim', explode(',', (string) $roles))));
                    if (!empty($rolesList)) {
                        $assignments[(string) $jKey] = $rolesList;
                    }
                }
            }
        }

        // 2. Try nested array input (journal_roles)
        if (empty($assignments) && $request->has('journal_roles')) {
            $raw = $request->input('journal_roles');
            if (is_array($raw)) {
                foreach ($raw as $jKey => $roles) {
                    $rolesList = is_array($roles)
                        ? array_values(array_filter(array_map('trim', $roles)))
                        : array_values(array_filter(array_map('trim', explode(',', (string) $roles))));
                    if (!empty($rolesList)) {
                        $assignments[(string) $jKey] = $rolesList;
                    }
                }
            }
        }

        // 3. Fallback: single journal_id and roles/role input
        if (empty($assignments)) {
            $jKey = ($request->filled('journal_id') && $request->journal_id !== 'global') ? (string) $request->journal_id : 'global';
            $rolesInput = $request->input('roles');
            if (is_array($rolesInput) && count($rolesInput) > 0) {
                $rolesList = array_values(array_filter(array_map('trim', $rolesInput)));
            } else {
                $rolesList = array_values(array_filter(array_map('trim', explode(',', (string) $request->input('role', 'Editorial Board Member')))));
            }
            if (empty($rolesList)) {
                $rolesList = ['Editorial Board Member'];
            }
            $assignments[$jKey] = $rolesList;
        }

        return $assignments;
    }

    /**
     * Store a board member from the central management page with multiple journal-wise roles.
     */
    public function storeGlobal(Request $request)
    {
        self::ensureSchemaExists();

        $assignments = $this->parseJournalAssignments($request);

        // Merge primary role for fallback/validation
        $primaryRoles = reset($assignments) ?: ['Editorial Board Member'];
        $roleString = implode(', ', $primaryRoles);
        $request->merge(['role' => $roleString]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'affiliation' => 'required|string|max:255',
            'postal_address' => 'nullable|string|max:1000',
            'country' => 'nullable|string|max:100',
            'is_international' => 'nullable|boolean',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:500',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_international'] = $request->boolean('is_international');

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('editorial_board', 'public');
        }

        $sortOrder = $validated['sort_order'] ?? 0;
        $email = $validated['email'] ?? null;
        $editorUid = 'ed_' . str_replace('-', '', (string) \Illuminate\Support\Str::uuid());

        foreach ($assignments as $jKey => $rolesList) {
            $journalId = ($jKey === 'global' || empty($jKey)) ? null : (int) $jKey;
            $roleForJournal = implode(', ', $rolesList);

            EditorialBoard::create([
                'editor_uid' => $editorUid,
                'journal_id' => $journalId,
                'name' => $validated['name'],
                'designation' => $validated['designation'] ?? null,
                'department' => $validated['department'] ?? null,
                'affiliation' => $validated['affiliation'],
                'postal_address' => $validated['postal_address'] ?? null,
                'country' => $validated['country'] ?? null,
                'is_international' => $validated['is_international'] ?? false,
                'email' => $email,
                'role' => $roleForJournal,
                'bio' => $validated['bio'] ?? null,
                'photo' => $photoPath,
                'sort_order' => $sortOrder,
            ]);
        }

        $count = count($assignments);
        return redirect()->route('admin.editorial-board.index')
            ->with('success', "Editorial board member added successfully with {$count} assigned journal appointment(s).");
    }

    /**
     * Update a board member from the central management page across assigned journals.
     */
    public function updateGlobal(Request $request, EditorialBoard $member)
    {
        self::ensureSchemaExists();

        $assignments = $this->parseJournalAssignments($request);

        $primaryRoles = reset($assignments) ?: ['Editorial Board Member'];
        $roleString = implode(', ', $primaryRoles);
        $request->merge(['role' => $roleString]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'affiliation' => 'required|string|max:255',
            'postal_address' => 'nullable|string|max:1000',
            'country' => 'nullable|string|max:100',
            'is_international' => 'nullable|boolean',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:500',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_international'] = $request->boolean('is_international');

        // Ensure editor_uid is set
        $editorUid = $member->editor_uid;
        if (empty($editorUid)) {
            $editorUid = 'ed_' . $member->id;
            $member->update(['editor_uid' => $editorUid]);
        }

        // Handle photo upload
        $photoPath = $member->photo;
        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $photoPath = $request->file('photo')->store('editorial_board', 'public');
        }

        $sortOrder = $validated['sort_order'] ?? 0;
        $email = $validated['email'] ?? null;

        // Find all existing records belonging to this editor
        $existingQuery = EditorialBoard::where('editor_uid', $editorUid);
        if (!empty($member->email)) {
            $existingQuery->orWhere('email', $member->email);
        }
        $existingQuery->orWhere('id', $member->id);
        $existingRecords = $existingQuery->get();

        $existingByJournal = [];
        foreach ($existingRecords as $rec) {
            $k = $rec->journal_id ? (string) $rec->journal_id : 'global';
            $existingByJournal[$k] = $rec;
        }

        // Synchronize each assigned journal with its selected roles
        foreach ($assignments as $jKey => $rolesList) {
            $journalId = ($jKey === 'global' || empty($jKey)) ? null : (int) $jKey;
            $roleForJournal = implode(', ', $rolesList);

            $payload = [
                'editor_uid' => $editorUid,
                'name' => $validated['name'],
                'designation' => $validated['designation'] ?? null,
                'department' => $validated['department'] ?? null,
                'affiliation' => $validated['affiliation'],
                'postal_address' => $validated['postal_address'] ?? null,
                'country' => $validated['country'] ?? null,
                'is_international' => $validated['is_international'] ?? false,
                'email' => $email,
                'journal_id' => $journalId,
                'role' => $roleForJournal,
                'bio' => $validated['bio'] ?? null,
                'photo' => $photoPath,
                'sort_order' => $sortOrder,
            ];

            if (isset($existingByJournal[$jKey])) {
                $existingByJournal[$jKey]->update($payload);
                unset($existingByJournal[$jKey]); // Handled
            } else {
                EditorialBoard::create($payload);
            }
        }

        // Any remaining records in $existingByJournal are journals that were unassigned
        foreach ($existingByJournal as $unassignedRecord) {
            // Delete unassigned journal record if not the only one remaining
            if (EditorialBoard::where('editor_uid', $editorUid)->count() > 1) {
                $unassignedRecord->delete();
            }
        }

        $count = count($assignments);
        return redirect()->route('admin.editorial-board.index')
            ->with('success', "Editorial board member updated successfully across {$count} assigned journal(s).");
    }

    /**
     * Delete a board member from the central management page.
     */
    public function destroyGlobal(Request $request, EditorialBoard $member)
    {
        $editorUid = $member->editor_uid;
        $deleteAll = $request->query('all') === '1';

        if ($deleteAll && !empty($editorUid)) {
            $all = EditorialBoard::where('editor_uid', $editorUid)->get();
            foreach ($all as $m) {
                if ($m->photo && Storage::disk('public')->exists($m->photo)) {
                    Storage::disk('public')->delete($m->photo);
                }
                $m->delete();
            }
            return redirect()->route('admin.editorial-board.index')
                ->with('success', 'Editorial board member removed from all journals.');
        }

        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            if (!EditorialBoard::where('photo', $member->photo)->where('id', '!=', $member->id)->exists()) {
                Storage::disk('public')->delete($member->photo);
            }
        }

        $member->delete();

        return redirect()->route('admin.editorial-board.index')
            ->with('success', 'Editorial board appointment removed successfully.');
    }

    /**
     * Update public Editorial Board page settings (titles, badges, text).
     */
    public function updatePageSettings(Request $request)
    {
        $validated = $request->validate([
            'badge' => 'nullable|string|max:100',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'join_title' => 'nullable|string|max:255',
            'join_text' => 'nullable|string',
            'join_url' => 'nullable|string|max:255',
        ]);

        \App\Models\Setting::set('editorial_board_badge', $validated['badge'] ?? 'Leadership');
        \App\Models\Setting::set('editorial_board_title', $validated['title'] ?? 'Editorial Board');
        \App\Models\Setting::set('editorial_board_subtitle', $validated['subtitle'] ?? '');
        \App\Models\Setting::set('editorial_board_join_title', $validated['join_title'] ?? 'Join Our Editorial Board');
        \App\Models\Setting::set('editorial_board_join_text', $validated['join_text'] ?? '');
        \App\Models\Setting::set('editorial_board_join_url', $validated['join_url'] ?? route('about.page', 'contact-information'));

        // Sync with Page model record if available
        $page = \App\Models\Page::where('category', 'about')->where('slug', 'editorial-board')->first();
        if ($page) {
            $page->update([
                'title' => $validated['title'],
                'meta_title' => $validated['title'] . ' | HJParam Publication',
                'meta_description' => \Illuminate\Support\Str::limit(strip_tags($validated['subtitle'] ?? ''), 160),
            ]);
        }

        return redirect()->route('admin.editorial-board.index')
            ->with('success', 'Editorial Board public page settings updated successfully.');
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
            if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'editor_uid')) {
                \Illuminate\Support\Facades\Schema::table('editorial_boards', function ($table) {
                    $table->string('editor_uid', 64)->nullable()->index()->after('id');
                });
            }
            try {
                \Illuminate\Support\Facades\DB::statement("UPDATE editorial_boards SET editor_uid = CONCAT('ed_', id) WHERE editor_uid IS NULL OR editor_uid = ''");
            } catch (\Throwable $e) {}
            if (!\Illuminate\Support\Facades\Schema::hasColumn('journals', 'editorial_responsibilities')) {
                \Illuminate\Support\Facades\Schema::table('journals', function ($table) {
                    $table->longText('editorial_responsibilities')->nullable()->after('aims_and_scope');
                });
            }

            // Allow journal_id to be nullable for central/publisher board members
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE editorial_boards MODIFY journal_id BIGINT UNSIGNED NULL");
            } catch (\Throwable $e) {}

            // Ensure role column can hold multiple comma-separated roles
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE editorial_boards MODIFY role VARCHAR(500) NOT NULL");
            } catch (\Throwable $e) {}

            // Seed default members if table is completely empty
            if (\App\Models\EditorialBoard::count() === 0) {
                self::seedDefaults();
            }
        } catch (\Throwable $e) {
            // Silently fallback if table operation already performed or restricted
        }
    }

    public static function seedDefaults(): void
    {
        $defaults = [
            [
                'name' => 'Prof. Dr. James Sterling',
                'role' => 'Editor-in-Chief',
                'affiliation' => 'Department of Bioengineering, University of Excellence, UK',
                'bio' => 'Department of Bioengineering, University of Excellence, UK. Specializing in molecular dynamics and cellular automation. Research areas: Bioengineering, Molecular Biology.',
                'sort_order' => 1,
                'journal_id' => null,
            ],
            [
                'name' => 'Prof. Sarah Jenkins',
                'role' => 'Section Editor',
                'affiliation' => 'Stanford University',
                'bio' => 'Stanford University. Author of over 50 peer-reviewed articles in prestigious journals. Field: Environmental Sciences.',
                'sort_order' => 2,
                'journal_id' => null,
            ],
            [
                'name' => 'Dr. Michael Chen',
                'role' => 'Section Editor',
                'affiliation' => 'MIT',
                'bio' => 'MIT. Author of over 50 peer-reviewed articles in prestigious journals. Field: Inorganic Chemistry.',
                'sort_order' => 3,
                'journal_id' => null,
            ],
            [
                'name' => 'Prof. Elena Rosetti',
                'role' => 'Section Editor',
                'affiliation' => 'Oxford University',
                'bio' => 'Oxford University. Author of over 50 peer-reviewed articles in prestigious journals. Field: Public Health.',
                'sort_order' => 4,
                'journal_id' => null,
            ],
            [
                'name' => 'Dr. David Kumar',
                'role' => 'Section Editor',
                'affiliation' => 'ETH Zurich',
                'bio' => 'ETH Zurich. Author of over 50 peer-reviewed articles in prestigious journals. Field: Quantum Physics.',
                'sort_order' => 5,
                'journal_id' => null,
            ],
        ];

        foreach ($defaults as $d) {
            EditorialBoard::create($d);
        }
    }
}
