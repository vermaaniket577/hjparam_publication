<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\CertificateIssuanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::with(['journal', 'authors'])
            ->latest()
            ->paginate(10);

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $journals = \App\Models\Journal::with(['volumes.issues'])->orderBy('title')->get();
        $issues = \App\Models\Issue::with('volume')->get();
        $submissions = \App\Models\Submission::with(['journal', 'user'])
            ->orderBy('id', 'desc')
            ->take(50)
            ->get();

        $selectedSubmission = null;
        if ($request->filled('from_submission')) {
            $selectedSubmission = \App\Models\Submission::with(['journal', 'user'])->find($request->from_submission);
        }

        $initialAuthors = old('authors');
        if (!$initialAuthors) {
            if ($selectedSubmission) {
                $initialAuthors = \App\Services\CertificateIssuanceService::extractAuthors($selectedSubmission);
            } else {
                $initialAuthors = [
                    ['name' => '', 'email' => '', 'affiliation' => '', 'is_corresponding' => 1],
                ];
            }
        }

        return view('admin.articles.create', compact('journals', 'issues', 'submissions', 'selectedSubmission', 'initialAuthors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'issue_id' => 'nullable|exists:issues,id',
            'submission_id' => 'nullable|exists:submissions,id',
            'title' => 'required|string|max:500',
            'slug' => 'required|string|unique:articles,slug',
            'abstract' => 'required|string',
            'keywords' => 'nullable|string',
            'doi' => 'nullable|string',
            'pdf_path' => 'nullable|file|mimes:pdf|max:20480', // Support up to 20MB
            'manuscript_file' => 'nullable|file|mimes:doc,docx|max:51200', // Word doc/docx up to 50MB
            'status' => 'required|string',
            'published_at' => 'nullable|date',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:255',
            'authors.*.email' => 'nullable|email|max:255',
            'authors.*.affiliation' => 'nullable|string|max:500',
            'authors.*.is_corresponding' => 'nullable|boolean',
        ]);

        // Auto-assign issue if not provided
        $issueId = $request->issue_id;
        if (empty($issueId)) {
            $journal = \App\Models\Journal::find($request->journal_id);
            if ($journal) {
                $targetYear = $journal->effective_starting_year ?? date('Y');
                $volume = \App\Models\Volume::firstOrCreate(
                    ['journal_id' => $journal->id, 'year' => $targetYear],
                    ['volume_number' => '1', 'month' => 'Regular Issue', 'is_published' => true]
                );
                $issue = \App\Models\Issue::firstOrCreate(
                    ['volume_id' => $volume->id, 'issue_number' => '1'],
                    ['publication_date' => now(), 'is_published' => true]
                );
                $issueId = $issue->id;
            }
        }

        Article::ensureManuscriptColumns();

        $article = new Article($request->except('authors', 'pdf_path', 'manuscript_file'));
        $article->issue_id = $issueId;

        if ($request->hasFile('pdf_path')) {
            $path = $request->file('pdf_path')->store('articles/pdf', 'public');
            $article->pdf_path = $path;
        }

        if ($request->hasFile('manuscript_file')) {
            $mFile = $request->file('manuscript_file');
            $ext = strtolower($mFile->getClientOriginalExtension() ?: 'docx');
            $safeName = 'manuscript_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $ext;
            $article->manuscript_path = $mFile->storeAs('articles/manuscripts', $safeName, 'local');
            $article->original_filename = $mFile->getClientOriginalName();
            $article->file_extension = $ext;
            $article->file_size = $mFile->getSize();
            $article->mime_type = $mFile->getClientMimeType() ?: ($ext === 'doc' ? 'application/msword' : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        }

        $article->submitted_at = now();
        if ($request->status === 'published' && !$request->published_at) {
            $article->published_at = now();
        }

        $article->save();

        // Create Authors
        foreach ($request->authors as $authorData) {
            $article->authors()->create($authorData);
        }

        // If created from submission, mark submission as published
        if ($request->filled('submission_id')) {
            $sub = \App\Models\Submission::find($request->submission_id);
            if ($sub && $article->status === 'published') {
                $sub->update(['status' => 'published']);
            }
        }

        // Issue certificates automatically if published
        if ($article->status === 'published') {
            try {
                CertificateIssuanceService::issueForArticle($article);
            } catch (\Throwable $e) {
                Log::warning('Automatic certificate issuance error on article create: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Manuscript published and author records successfully registered.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::with('authors')->findOrFail($id);
        $journals = \App\Models\Journal::orderBy('title')->get();
        $issues = \App\Models\Issue::with('volume')->get();

        return view('admin.articles.edit', compact('article', 'journals', 'issues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'issue_id' => 'nullable|exists:issues,id',
            'title' => 'required|string|max:500',
            'slug' => 'required|string|unique:articles,slug,' . $article->id,
            'abstract' => 'required|string',
            'keywords' => 'nullable|string',
            'doi' => 'nullable|string',
            'pdf_path' => 'nullable|file|mimes:pdf|max:20480',
            'remove_pdf' => 'nullable|boolean',
            'manuscript_file' => 'nullable|file|mimes:doc,docx|max:51200',
            'remove_manuscript' => 'nullable|boolean',
            'status' => 'required|string',
            'published_at' => 'nullable|date',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:255',
            'authors.*.email' => 'nullable|email|max:255',
            'authors.*.affiliation' => 'nullable|string|max:500',
            'authors.*.is_corresponding' => 'nullable|boolean',
        ]);

        Article::ensureManuscriptColumns();

        $article->fill($request->except('authors', 'pdf_path', 'remove_pdf', 'manuscript_file', 'remove_manuscript'));

        if ($request->boolean('remove_pdf')) {
            if ($article->pdf_path && \Storage::disk('public')->exists($article->pdf_path)) {
                \Storage::disk('public')->delete($article->pdf_path);
            }
            $article->pdf_path = null;
        } elseif ($request->hasFile('pdf_path')) {
            // Option: delete old PDF
            if ($article->pdf_path && \Storage::disk('public')->exists($article->pdf_path)) {
                \Storage::disk('public')->delete($article->pdf_path);
            }
            $path = $request->file('pdf_path')->store('articles/pdf', 'public');
            $article->pdf_path = $path;
        }

        // Handle Original Word Manuscript replacement or deletion
        if ($request->boolean('remove_manuscript')) {
            if ($article->manuscript_path && \Storage::disk('local')->exists($article->manuscript_path)) {
                \Storage::disk('local')->delete($article->manuscript_path);
            }
            $article->manuscript_path = null;
            $article->original_filename = null;
            $article->file_extension = null;
            $article->file_size = null;
            $article->mime_type = null;
        } elseif ($request->hasFile('manuscript_file')) {
            if ($article->manuscript_path && \Storage::disk('local')->exists($article->manuscript_path)) {
                \Storage::disk('local')->delete($article->manuscript_path);
            }
            $mFile = $request->file('manuscript_file');
            $ext = strtolower($mFile->getClientOriginalExtension() ?: 'docx');
            $safeName = 'manuscript_' . $article->id . '_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $ext;
            $article->manuscript_path = $mFile->storeAs('articles/manuscripts', $safeName, 'local');
            $article->original_filename = $mFile->getClientOriginalName();
            $article->file_extension = $ext;
            $article->file_size = $mFile->getSize();
            $article->mime_type = $mFile->getClientMimeType() ?: ($ext === 'doc' ? 'application/msword' : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        }

        if ($request->status === 'published' && !$article->published_at) {
            $article->published_at = now();
        }

        $article->save();

        // Update Authors and sync linked Submission & Publication Certificates atomically
        $cleanAuthors = [];
        foreach ($request->authors as $authorData) {
            if (!empty(trim((string) ($authorData['name'] ?? '')))) {
                $cleanAuthors[] = [
                    'name' => trim((string) $authorData['name']),
                    'email' => !empty($authorData['email']) ? trim((string) $authorData['email']) : null,
                    'affiliation' => !empty($authorData['affiliation']) ? trim((string) $authorData['affiliation']) : null,
                    'is_corresponding' => !empty($authorData['is_corresponding']) ? 1 : 0,
                ];
            }
        }

        if (!empty($cleanAuthors)) {
            AuthorController::persistAndSyncAuthors($article, $cleanAuthors);
        } elseif ($article->status === 'published') {
            try {
                CertificateIssuanceService::issueForArticle($article);
            } catch (\Throwable $e) {
                Log::warning('Automatic certificate issuance error on article update: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article registry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        if ($article->pdf_path && \Storage::disk('public')->exists($article->pdf_path)) {
            \Storage::disk('public')->delete($article->pdf_path);
        }

        if ($article->manuscript_path && \Storage::disk('local')->exists($article->manuscript_path)) {
            \Storage::disk('local')->delete($article->manuscript_path);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article and associated assets removed.');
    }

    /**
     * Quick toggle published / draft status for an article.
     */
    public function togglePublish(Article $article)
    {
        $newStatus = $article->status === 'published' ? 'under_review' : 'published';
        $article->status = $newStatus;
        if ($newStatus === 'published' && !$article->published_at) {
            $article->published_at = now();
        }
        $article->save();

        if ($newStatus === 'published') {
            try {
                CertificateIssuanceService::issueForArticle($article);
            } catch (\Throwable $e) {
                Log::warning('Certificate error on toggle publish: ' . $e->getMessage());
            }
        }

        return back()->with('success', "Article '{$article->title}' is now " . ucfirst(str_replace('_', ' ', $newStatus)) . '.');
    }
}
