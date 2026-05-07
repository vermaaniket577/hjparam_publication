<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Issue;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Submission::with(['user', 'journal', 'article']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('journal')) {
            $query->where('journal_id', $request->journal);
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        $submission->load(['user', 'journal', 'issue.volume', 'article.journal', 'reviews.reviewer']);
        $issues = Issue::with('volume')
            ->whereHas('volume', fn ($query) => $query->where('journal_id', $submission->journal_id))
            ->orderByDesc('publication_date')
            ->get();

        // fetch potential reviewers: users with role reviewer or editor
        $reviewers = \App\Models\User::whereIn('role', ['reviewer', 'editor', 'admin'])
            ->where('id', '!=', $submission->user_id) // Cannot review own paper
            ->orderBy('name')
            ->get();

        return view('admin.submissions.show', compact('submission', 'reviewers', 'issues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submission $submission)
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,verified,accepted,rejected,published',
        ]);

        $submission->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Submission status updated successfully.');
    }

    /**
     * Assign a reviewer to the submission.
     */
    public function assign(Request $request, Submission $submission)
    {
        $request->validate([
            'reviewer_id' => 'required|exists:users,id',
        ]);

        // Check if already assigned
        $exists = \App\Models\Review::where('submission_id', $submission->id)
            ->where('reviewer_id', $request->reviewer_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'This reviewer is already assigned to this submission.');
        }

        \App\Models\Review::create([
            'submission_id' => $submission->id,
            'reviewer_id' => $request->reviewer_id,
        ]);

        // Update submission status to under_review if needed
        if ($submission->status === 'submitted') {
            $submission->update(['status' => 'under_review']);
        }

        return back()->with('success', 'Reviewer assigned successfully.');
    }

    /**
     * Publish a verified submission as a public website article.
     */
    public function publish(Request $request, Submission $submission)
    {
        if ($submission->article) {
            return redirect()
                ->route('admin.articles.edit', $submission->article)
                ->with('success', 'This submission has already been published. You can edit the article here.');
        }

        if (!in_array($submission->status, ['verified', 'accepted'], true)) {
            return back()->with('error', 'Please verify or accept this submission before publishing it on the website.');
        }

        $validated = $request->validate([
            'issue_id' => [
                'required',
                Rule::exists('issues', 'id')->where(function ($query) use ($submission) {
                    $query->whereIn('volume_id', function ($volumeQuery) use ($submission) {
                        $volumeQuery->select('id')
                            ->from('volumes')
                            ->where('journal_id', $submission->journal_id);
                    });
                }),
            ],
            'keywords' => 'nullable|string|max:1000',
            'doi' => 'nullable|string|max:255|unique:articles,doi',
            'published_at' => 'nullable|date',
        ]);

        $article = DB::transaction(function () use ($submission, $validated) {
            $publishedAt = isset($validated['published_at'])
                ? \Carbon\Carbon::parse($validated['published_at'])
                : now();

            $article = Article::create([
                'journal_id' => $submission->journal_id,
                'issue_id' => $validated['issue_id'],
                'submission_id' => $submission->id,
                'user_id' => $submission->user_id,
                'title' => $submission->title,
                'slug' => $this->uniqueArticleSlug($submission->title),
                'abstract' => $submission->abstract,
                'keywords' => $validated['keywords'] ?? $submission->keywords,
                'doi' => $validated['doi'] ?? null,
                'pdf_path' => $submission->file_path,
                'status' => 'published',
                'submitted_at' => $submission->created_at,
                'accepted_at' => now(),
                'published_at' => $publishedAt,
            ]);

            $article->authors()->create([
                'name' => $submission->user->name,
                'email' => $submission->user->email,
                'affiliation' => $submission->user->affiliation ?: 'Not provided',
                'is_corresponding' => true,
            ]);

            $submission->update([
                'issue_id' => $validated['issue_id'],
                'keywords' => $validated['keywords'] ?? $submission->keywords,
                'status' => 'published',
            ]);

            return $article;
        });

        return redirect()
            ->route('admin.articles.edit', $article)
            ->with('success', 'Submission verified and published on the website successfully.');
    }

    private function uniqueArticleSlug(string $title): string
    {
        $baseSlug = Str::slug($title) ?: 'article';
        $slug = $baseSlug;
        $counter = 2;

        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Download the submission manuscript.
     */
    public function download(Submission $submission)
    {
        // Favor binary content from database
        if ($submission->binary_content) {
            $filename = $submission->filename ?? 'manuscript_' . $submission->id . '.' . ($submission->extension ?? 'docx');
            return response($submission->binary_content)
                ->header('Content-Type', $submission->mime_type ?? 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        // Fallback for disk
        $filePath = $submission->file_path;
        $fullPath = storage_path('app/' . $filePath);

        if (!$filePath || !file_exists($fullPath)) {
            \Log::warning("Manuscript file missing for download #{$submission->id}. Serving placeholder.");

            $sampleDocx = storage_path('app/samples/sample.docx');
            $samplePdf = storage_path('app/samples/sample.pdf');
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $fallbackPath = ($extension === 'docx' || $extension === 'doc') ? $sampleDocx : $samplePdf;

            if (file_exists($fallbackPath)) {
                return response()->download($fallbackPath, 'manuscript_placeholder_' . $submission->id . '.' . $extension);
            }

            return back()->with('error', 'Manuscript file not found.');
        }

        $fileName = 'manuscript_' . $submission->id . '_' . basename($filePath);

        return response()->download($fullPath, $fileName, [
            'Content-Type' => mime_content_type($fullPath),
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * View the submission manuscript inline.
     */
    public function view(Submission $submission)
    {
        // 1. Retrieve Binary Content (LONGBLOB)
        $binaryData = $submission->binary_content;

        if (!$binaryData) {
            // Fallback to disk if binary is missing
            $filePath = $submission->file_path;
            $fullPath = storage_path('app/' . $filePath);

            if ($filePath && file_exists($fullPath)) {
                $binaryData = file_get_contents($fullPath);
            } else {
                abort(404, 'Scholarly document not found.');
            }
        }

        // 2. Determine High-Precision MIME Type
        $contentType = $submission->mime_type ?? 'application/pdf';
        $filename = $submission->filename ?? 'manuscript_' . $submission->id;

        // 3. Stream Binary Direct with Inline Header
        return response($binaryData)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Content-Length', strlen($binaryData))
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Cache-Control', 'private, max-age=3600');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Submission $submission)
    {
        //
    }
}
