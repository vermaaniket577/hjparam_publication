<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Author;
use App\Models\Certificate;
use App\Models\Journal;
use App\Services\CertificateIssuanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AuthorController extends Controller
{
    /**
     * Central Journal Paper Authors Management hub (filterable by journal, status, or search).
     */
    public function index(Request $request)
    {
        $journals = Journal::orderBy('title')->get();
        $selectedJournal = null;

        $query = Article::with(['journal', 'issue.volume', 'authors', 'submission'])
            ->latest('id');

        if ($request->filled('journal_id')) {
            $query->where('journal_id', $request->journal_id);
            $selectedJournal = $journals->firstWhere('id', (int) $request->journal_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('doi', 'like', "%{$term}%")
                    ->orWhereHas('authors', function ($aq) use ($term) {
                        $aq->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%")
                            ->orWhere('affiliation', 'like', "%{$term}%");
                    });
            });
        }

        $articles = $query->paginate(12)->withQueryString();

        $stats = [
            'total_articles' => $selectedJournal
                ? Article::where('journal_id', $selectedJournal->id)->count()
                : Article::count(),
            'total_authors' => $selectedJournal
                ? Author::whereHas('article', fn ($q) => $q->where('journal_id', $selectedJournal->id))->count()
                : Author::count(),
            'published_articles' => $selectedJournal
                ? Article::where('journal_id', $selectedJournal->id)->where('status', 'published')->count()
                : Article::where('status', 'published')->count(),
        ];

        return view('admin.authors.index', compact('articles', 'journals', 'selectedJournal', 'stats'));
    }

    /**
     * Per-Journal Authors Management view (accessible directly from Admin -> Journals).
     */
    public function journalAuthors(Request $request, Journal $journal)
    {
        $journals = Journal::orderBy('title')->get();
        $selectedJournal = $journal;

        $query = Article::with(['journal', 'issue.volume', 'authors', 'submission'])
            ->where('journal_id', $journal->id)
            ->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = trim($request->q);
            $query->where(function ($sub) use ($term) {
                $sub->where('title', 'like', "%{$term}%")
                    ->orWhere('doi', 'like', "%{$term}%")
                    ->orWhereHas('authors', function ($aq) use ($term) {
                        $aq->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%")
                            ->orWhere('affiliation', 'like', "%{$term}%");
                    });
            });
        }

        $articles = $query->paginate(12)->withQueryString();

        $stats = [
            'total_articles' => Article::where('journal_id', $journal->id)->count(),
            'total_authors' => Author::whereHas('article', fn ($q) => $q->where('journal_id', $journal->id))->count(),
            'published_articles' => Article::where('journal_id', $journal->id)->where('status', 'published')->count(),
        ];

        return view('admin.authors.index', compact('articles', 'journals', 'selectedJournal', 'stats'));
    }

    /**
     * Dedicated interactive Author Manager for a single journal article.
     */
    public function manage(Article $article)
    {
        $article->load(['journal', 'issue.volume', 'authors', 'submission']);

        $initialAuthors = old('authors');
        if (!$initialAuthors) {
            $initialAuthors = $article->authors->map(function (Author $a) {
                return [
                    'id' => $a->id,
                    'name' => $a->name,
                    'email' => $a->email ?? '',
                    'affiliation' => $a->affiliation ?? '',
                    'is_corresponding' => $a->is_corresponding ? 1 : 0,
                ];
            })->values()->toArray();

            if (empty($initialAuthors)) {
                $initialAuthors = [[
                    'id' => null,
                    'name' => '',
                    'email' => '',
                    'affiliation' => '',
                    'is_corresponding' => 1,
                ]];
            }
        }

        return view('admin.authors.manage', compact('article', 'initialAuthors'));
    }

    /**
     * Atomically save, update, reorder, add, or remove authors for an article
     * and synchronize linked Submission records & Publication Certificates.
     */
    public function sync(Request $request, Article $article)
    {
        $validated = $request->validate([
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:255',
            'authors.*.email' => 'nullable|email|max:255',
            'authors.*.affiliation' => 'nullable|string|max:500',
            'authors.*.is_corresponding' => 'nullable',
        ]);

        $cleanAuthors = [];
        foreach ($validated['authors'] as $item) {
            $name = trim((string) ($item['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $cleanAuthors[] = [
                'name' => $name,
                'email' => !empty($item['email']) ? trim((string) $item['email']) : null,
                'affiliation' => !empty($item['affiliation']) ? trim((string) $item['affiliation']) : null,
                'is_corresponding' => !empty($item['is_corresponding']) ? 1 : 0,
            ];
        }

        if (empty($cleanAuthors)) {
            return back()->withErrors(['authors' => 'At least one author name is required.'])->withInput();
        }

        self::persistAndSyncAuthors($article, $cleanAuthors);

        $count = count($cleanAuthors);
        return redirect()
            ->route('admin.articles.authors.manage', $article)
            ->with('success', "Authors updated successfully ({$count} " . ($count === 1 ? 'author' : 'authors') . " saved and synced to the live journal page, citations, and certificates).");
    }

    /**
     * Quick-add a single author to an article directly from the Journal/Authors list.
     */
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'affiliation' => 'nullable|string|max:500',
            'is_corresponding' => 'nullable',
        ]);

        $article->load('authors');
        $existing = $article->authors->map(fn (Author $a) => [
            'name' => $a->name,
            'email' => $a->email,
            'affiliation' => $a->affiliation,
            'is_corresponding' => $a->is_corresponding ? 1 : 0,
        ])->toArray();

        $existing[] = [
            'name' => trim($validated['name']),
            'email' => !empty($validated['email']) ? trim($validated['email']) : null,
            'affiliation' => !empty($validated['affiliation']) ? trim($validated['affiliation']) : null,
            'is_corresponding' => !empty($validated['is_corresponding']) ? 1 : 0,
        ];

        self::persistAndSyncAuthors($article, $existing);

        return back()->with('success', "Author \"{$validated['name']}\" added to \"{$article->title}\" and synced.");
    }

    /**
     * Quick-remove a single author from an article (preventing deletion of the last remaining author).
     */
    public function destroy(Article $article, Author $author)
    {
        if ((int) $author->article_id !== (int) $article->id) {
            abort(404);
        }

        $article->load('authors');
        if ($article->authors->count() <= 1) {
            return back()->with('error', 'An article must keep at least one author. Edit the author name instead of deleting the last author.');
        }

        $removedName = $author->name;
        $remaining = $article->authors
            ->reject(fn (Author $a) => (int) $a->id === (int) $author->id)
            ->map(fn (Author $a) => [
                'name' => $a->name,
                'email' => $a->email,
                'affiliation' => $a->affiliation,
                'is_corresponding' => $a->is_corresponding ? 1 : 0,
            ])
            ->values()
            ->toArray();

        self::persistAndSyncAuthors($article, $remaining);

        return back()->with('success', "Author \"{$removedName}\" removed and article records synced.");
    }

    /**
     * Shared atomic persistence & synchronization routine:
     *  1. Replaces Article authors in exact specified order
     *  2. Synchronizes linked Submission authors_data / scholars_data
     *  3. Cleans stale certificates & re-issues Publication Certificates if published
     */
    public static function persistAndSyncAuthors(Article $article, array $cleanAuthors): void
    {
        DB::transaction(function () use ($article, $cleanAuthors) {
            $article->authors()->delete();

            foreach ($cleanAuthors as $authorData) {
                $article->authors()->create([
                    'name' => $authorData['name'],
                    'email' => $authorData['email'] ?? null,
                    'affiliation' => $authorData['affiliation'] ?? null,
                    'is_corresponding' => !empty($authorData['is_corresponding']) ? 1 : 0,
                ]);
            }

            // Touch updated_at so caches/timestamps reflect the latest change
            $article->touch();

            // Sync linked Submission if present
            if ($article->submission_id && $article->submission) {
                $submissionAuthors = [];
                foreach ($cleanAuthors as $idx => $a) {
                    $parts = explode(' ', trim($a['name']), 2);
                    $submissionAuthors[] = [
                        'name' => $a['name'],
                        'first_name' => $parts[0] ?? '',
                        'last_name' => $parts[1] ?? '',
                        'email' => $a['email'] ?? null,
                        'institution' => $a['affiliation'] ?? null,
                        'affiliation' => $a['affiliation'] ?? null,
                        'institute' => $a['affiliation'] ?? null,
                        'role' => $idx === 0 ? 'author' : 'co_author',
                        'is_corresponding' => !empty($a['is_corresponding']) ? 1 : 0,
                    ];
                }

                $article->submission->authors_data = $submissionAuthors;
                $article->submission->scholars_data = $submissionAuthors;
                $article->submission->save();
            }
        });

        // Reload fresh authors and refresh publication certificates if published
        $article->load('authors');

        if ($article->status === 'published') {
            try {
                CertificateIssuanceService::ensureTableExists();
                if (Schema::hasTable('certificates')) {
                    $currentNames = $article->authors->pluck('name')->all();
                    Certificate::where('article_id', $article->id)
                        ->whereNotIn('recipient_name', $currentNames)
                        ->delete();
                }
                CertificateIssuanceService::issueForArticle($article);
            } catch (\Throwable $e) {
                Log::warning('Certificate sync error after author update: ' . $e->getMessage());
            }
        }
    }
}
