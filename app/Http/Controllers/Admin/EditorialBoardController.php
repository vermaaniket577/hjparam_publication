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
            $query->where('role', $request->role);
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

        $stats = [
            'total' => EditorialBoard::count(),
            'chief' => EditorialBoard::where('role', 'like', '%Chief%')->count(),
            'section' => EditorialBoard::where('role', 'like', '%Section%')->count(),
            'other' => EditorialBoard::where('role', 'not like', '%Chief%')->where('role', 'not like', '%Section%')->count(),
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
     * Store a board member from the central management page.
     */
    public function storeGlobal(Request $request)
    {
        self::ensureSchemaExists();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'journal_id' => 'nullable',
            'affiliation' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['journal_id']) || $validated['journal_id'] === 'global') {
            $validated['journal_id'] = null;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('editorial_board', 'public');
            $validated['photo'] = $path;
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
            unset($validated['sort_order']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
            unset($validated['email']);
        }

        EditorialBoard::create($validated);

        return redirect()->route('admin.editorial-board.index')
            ->with('success', 'Editorial board member added successfully.');
    }

    /**
     * Update a board member from the central management page.
     */
    public function updateGlobal(Request $request, EditorialBoard $member)
    {
        self::ensureSchemaExists();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'journal_id' => 'nullable',
            'affiliation' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'role' => 'required|string|max:100',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['journal_id']) || $validated['journal_id'] === 'global') {
            $validated['journal_id'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $path = $request->file('photo')->store('editorial_board', 'public');
            $validated['photo'] = $path;
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'sort_order')) {
            unset($validated['sort_order']);
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('editorial_boards', 'email')) {
            unset($validated['email']);
        }

        $member->update($validated);

        return redirect()->route('admin.editorial-board.index')
            ->with('success', 'Editorial board member updated successfully.');
    }

    /**
     * Delete a board member from the central management page.
     */
    public function destroyGlobal(EditorialBoard $member)
    {
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('admin.editorial-board.index')
            ->with('success', 'Editorial board member deleted successfully.');
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
            if (!\Illuminate\Support\Facades\Schema::hasColumn('journals', 'editorial_responsibilities')) {
                \Illuminate\Support\Facades\Schema::table('journals', function ($table) {
                    $table->longText('editorial_responsibilities')->nullable()->after('aims_and_scope');
                });
            }

            // Allow journal_id to be nullable for central/publisher board members
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE editorial_boards MODIFY journal_id BIGINT UNSIGNED NULL");
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
