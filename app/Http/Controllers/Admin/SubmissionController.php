<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\User;
use App\Models\Review;
use App\Models\Certificate;
use App\Models\Article;
use App\Models\Journal;
use App\Models\Volume;
use App\Models\Issue;
use App\Models\Author;
use App\Services\CertificateIssuanceService;
use App\Mail\SubmissionStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Dispatch an informational notification email to the author whenever submission status/details change.
     */
    protected function notifyAuthor(Submission $submission, string $type, array $data = []): bool
    {
        try {
            $submission->loadMissing(['user', 'journal', 'conference']);

            $authorEmail = $submission->user->email ?? null;
            if (!$authorEmail || !filter_var($authorEmail, FILTER_VALIDATE_EMAIL)) {
                Log::warning("Submission status notification skipped: Author email missing or invalid for submission #{$submission->id}");
                return false;
            }

            Mail::to($authorEmail)->send(new SubmissionStatusNotification($submission, $type, $data));

            Log::info("Submission notification email sent successfully to {$authorEmail} for submission #{$submission->id} ({$type})");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to send submission status notification email: " . $e->getMessage(), [
                'submission_id' => $submission->id,
                'type' => $type,
                'exception' => $e,
            ]);
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Submission::with(['user', 'journal', 'conference', 'editor', 'article.journal', 'article.authors']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('conference_id')) {
            $query->where('conference_id', $request->conference_id);
        }

        if ($request->filled('journal_id')) {
            $query->where('journal_id', $request->journal_id);
        }

        $submissions = $query->orderBy('created_at', 'desc')->paginate(15);
        $conferences = \App\Models\Conference::orderBy('title')->get();

        return view('admin.submissions.index', compact('submissions', 'conferences'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Submission $submission)
    {
        CertificateIssuanceService::ensureTableExists();

        $relations = [
            'user',
            'journal',
            'conference',
            'editor',
            'reviews.reviewer',
            'article.journal',
            'article.issue.volume',
            'article.authors',
        ];
        if (\Illuminate\Support\Facades\Schema::hasTable('certificates')) {
            $relations[] = 'certificates';
        }
        $submission->load($relations);
        
        // Potential editors & reviewers
        $editors = User::whereIn('role', ['editor', 'admin'])->orderBy('name')->get();
        $reviewers = User::whereIn('role', ['reviewer', 'editor', 'admin'])
            ->where('id', '!=', $submission->user_id)
            ->orderBy('name')
            ->get();

        $journals = Journal::with(['volumes' => function ($q) {
            $q->orderBy('year', 'desc')->with('issues');
        }])->orderBy('title')->get();

        $authorsList = CertificateIssuanceService::extractAuthors($submission);

        return view('admin.submissions.show', compact('submission', 'editors', 'reviewers', 'journals', 'authorsList'));
    }

    /**
     * Directly publish a submission into an official Journal Article.
     */
    public function publishToJournal(Request $request, Submission $submission)
    {
        $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'issue_id' => 'nullable|exists:issues,id',
            'title' => 'required|string|max:500',
            'abstract' => 'required|string',
            'keywords' => 'nullable|string',
            'doi' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'pdf_file' => 'nullable|file|mimes:pdf|max:30720',
            'manuscript_file' => 'nullable|file|mimes:doc,docx|max:51200',
            'authors' => 'nullable|array',
            'authors.*.name' => 'nullable|string|max:255',
            'authors.*.email' => 'nullable|email|max:255',
            'authors.*.affiliation' => 'nullable|string|max:500',
            'authors.*.is_corresponding' => 'nullable',
        ]);

        $journal = Journal::findOrFail($request->journal_id);

        // Ensure an Issue exists for this Journal
        $issueId = $request->issue_id;
        if (empty($issueId)) {
            $targetYear = $journal->effective_starting_year ?? date('Y');
            $volume = Volume::firstOrCreate(
                ['journal_id' => $journal->id, 'year' => $targetYear],
                ['volume_number' => '1', 'month' => 'Regular Issue', 'is_published' => true]
            );

            $issue = Issue::firstOrCreate(
                ['volume_id' => $volume->id, 'issue_number' => '1'],
                ['publication_date' => now(), 'is_published' => true]
            );
            $issueId = $issue->id;
        }

        // Generate unique slug
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug ?: 'article-' . time();
        $counter = 1;
        $existingArticleId = $submission->article?->id;
        while (Article::where('slug', $slug)->where('id', '!=', $existingArticleId ?? 0)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // DOI Generation if empty
        $doi = $request->doi;
        if (empty($doi)) {
            $year = $request->published_at ? date('Y', strtotime($request->published_at)) : date('Y');
            $doi = '10.5281/hjparam.' . $journal->slug . '.' . $year . '.' . str_pad($submission->id, 4, '0', STR_PAD_LEFT);
        }

        Article::ensureManuscriptColumns();

        // Handle Manuscript PDF
        $pdfPath = $submission->article?->pdf_path;
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('articles/pdf', 'public');
        } elseif (empty($pdfPath) && !$request->hasFile('manuscript_file')) {
            // Check if submission file on disk is an actual PDF
            $candidates = [
                !empty($submission->file_path) ? Storage::disk('local')->path($submission->file_path) : null,
                !empty($submission->file_path) ? storage_path('app/' . $submission->file_path) : null,
                !empty($submission->file_path) ? storage_path('app/private/' . $submission->file_path) : null,
            ];
            foreach ($candidates as $cand) {
                if (!empty($cand) && file_exists($cand) && !is_dir($cand)) {
                    $h = @fopen($cand, 'rb');
                    $header = $h ? fread($h, 1024) : '';
                    if ($h) fclose($h);
                    if ($header !== false && str_contains($header, '%PDF-')) {
                        try {
                            $newFilename = 'articles/pdf/manuscript_' . $submission->id . '_' . basename($cand);
                            Storage::disk('public')->put($newFilename, file_get_contents($cand));
                            $pdfPath = $newFilename;
                            break;
                        } catch (\Throwable $e) {}
                    }
                }
            }

            if (empty($pdfPath) && !empty($submission->binary_content)) {
                $binStr = is_resource($submission->binary_content) ? stream_get_contents($submission->binary_content) : (string)$submission->binary_content;
                if (str_contains(substr($binStr, 0, 1024), '%PDF-')) {
                    try {
                        $newFilename = 'articles/pdf/article_' . $submission->id . '_' . time() . '.pdf';
                        Storage::disk('public')->put($newFilename, $binStr);
                        $pdfPath = $newFilename;
                    } catch (\Throwable $e) {}
                }
            }
        }

        // Handle Original Microsoft Word Document (.doc / .docx)
        $existingArticle = $submission->article;
        $manuscriptPath = $existingArticle?->manuscript_path;
        $originalFilename = $existingArticle?->original_filename;
        $fileExtension = $existingArticle?->file_extension;
        $fileSize = $existingArticle?->file_size;
        $mimeType = $existingArticle?->mime_type;

        if ($request->hasFile('manuscript_file')) {
            $mFile = $request->file('manuscript_file');
            $ext = strtolower($mFile->getClientOriginalExtension() ?: 'docx');
            $safeName = 'manuscript_' . $submission->id . '_' . time() . '_' . Str::random(8) . '.' . $ext;
            $manuscriptPath = $mFile->storeAs('articles/manuscripts', $safeName, 'local');
            $originalFilename = $mFile->getClientOriginalName();
            $fileExtension = $ext;
            $fileSize = $mFile->getSize();
            $mimeType = $mFile->getClientMimeType() ?: ($ext === 'doc' ? 'application/msword' : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        } elseif (empty($manuscriptPath) && empty($pdfPath)) {
            // Inherit directly from submitted manuscript only when no PDF was uploaded/found
            if (!empty($submission->file_path)) {
                $manuscriptPath = $submission->file_path;
                $originalFilename = $submission->filename ?: ($request->title . '.' . ($submission->extension ?: 'docx'));
                $fileExtension = $submission->extension ?: 'docx';
                $fileSize = $submission->file_size;
                $mimeType = $submission->mime_type;
            }
        }

        // Prepare Article data (safe against schema variations without user_id)
        $articleData = [
            'journal_id' => $journal->id,
            'issue_id' => $issueId,
            'title' => $request->title,
            'slug' => $slug,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords ?? $submission->keywords,
            'doi' => $doi,
            'pdf_path' => $pdfPath,
            'manuscript_path' => $manuscriptPath,
            'original_filename' => $originalFilename,
            'file_extension' => $fileExtension,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'status' => 'published',
            'submitted_at' => $submission->created_at ?? now(),
            'accepted_at' => now(),
            'published_at' => $request->published_at ? \Carbon\Carbon::parse($request->published_at) : now(),
        ];

        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('articles', 'user_id')) {
                $articleData['user_id'] = $submission->user_id;
            }
        } catch (\Throwable $e) {}

        // Create or update Article
        $article = Article::updateOrCreate(
            ['submission_id' => $submission->id],
            $articleData
        );

        // Process authors: use provided or fallback to extracted
        $authorsData = $request->input('authors');
        if (empty($authorsData) || !is_array($authorsData)) {
            $extracted = CertificateIssuanceService::extractAuthors($submission);
            $authorsData = array_map(function ($item) {
                return [
                    'name' => $item['name'],
                    'email' => $item['email'] ?? null,
                    'affiliation' => $item['institution'] ?? ($item['department'] ?? 'Academic Researcher'),
                    'is_corresponding' => ($item['role'] ?? '') === 'author' ? 1 : 0,
                ];
            }, $extracted);
        }

        $cleanAuthors = [];
        foreach ($authorsData as $authorItem) {
            $name = trim((string) ($authorItem['name'] ?? ''));
            if ($name !== '') {
                $cleanAuthors[] = [
                    'name' => $name,
                    'email' => !empty($authorItem['email']) ? trim((string) $authorItem['email']) : null,
                    'affiliation' => !empty($authorItem['affiliation']) ? trim((string) $authorItem['affiliation']) : (!empty($authorItem['institution']) ? trim((string) $authorItem['institution']) : null),
                    'is_corresponding' => !empty($authorItem['is_corresponding']) ? 1 : 0,
                ];
            }
        }

        if (empty($cleanAuthors)) {
            $cleanAuthors[] = [
                'name' => $submission->user->name ?? 'Author',
                'email' => $submission->user->email ?? null,
                'affiliation' => 'Academic Researcher',
                'is_corresponding' => 1,
            ];
        }

        // Atomically sync authors, submission scholars_data, and certificates
        AuthorController::persistAndSyncAuthors($article, $cleanAuthors);

        // Update submission status to published
        $submission->update([
            'status' => 'published',
            'journal_id' => $journal->id,
        ]);

        // Send email to author
        $this->notifyAuthor($submission, 'status_change', [
            'new_status' => 'published',
            'journal_title' => $journal->title,
            'article_title' => $article->title,
            'article_url' => route('articles.show', [$journal->slug, $article->slug]),
        ]);

        $articleUrl = route('articles.show', [$journal->slug, $article->slug]);
        return redirect()->route('admin.submissions.show', $submission)
            ->with('success', "🎉 Paper officially published in '{$journal->title}'! View live article at: {$articleUrl}");
    }

    /**
     * Update the general submission status.
     */
    public function update(Request $request, Submission $submission)
    {
        // Handle authors update if submitted
        if ($request->has('update_authors')) {
            $request->validate([
                'authors' => 'required|array|min:1',
                'authors.*.name' => 'required|string|max:255',
                'authors.*.email' => 'nullable|email|max:255',
                'authors.*.institution' => 'nullable|string|max:500',
                'authors.*.department' => 'nullable|string|max:255',
                'authors.*.designation' => 'nullable|string|max:255',
                'authors.*.mobile' => 'nullable|string|max:50',
                'authors.*.orcid' => 'nullable|string|max:100',
            ]);

            $cleanAuthors = [];
            foreach ($request->authors as $idx => $a) {
                $nameParts = explode(' ', trim($a['name'] ?? ''), 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $cleanAuthors[] = [
                    'name' => trim($a['name']),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $a['email'] ?? null,
                    'institution' => $a['institution'] ?? ($a['affiliation'] ?? null),
                    'affiliation' => $a['institution'] ?? ($a['affiliation'] ?? null),
                    'institute' => $a['institution'] ?? ($a['affiliation'] ?? null),
                    'department' => $a['department'] ?? null,
                    'designation' => $a['designation'] ?? null,
                    'mobile' => $a['mobile'] ?? null,
                    'orcid' => $a['orcid'] ?? null,
                    'role' => $idx === 0 ? 'author' : 'co_author',
                    'is_corresponding' => $idx === 0 ? 1 : (!empty($a['is_corresponding']) ? 1 : 0),
                ];
            }

            $submission->authors_data = $cleanAuthors;
            $submission->scholars_data = $cleanAuthors;
            $submission->save();

            // Also synchronize with published Article authors if an Article exists
            if ($submission->article) {
                $submission->article->authors()->delete();
                foreach ($cleanAuthors as $authorItem) {
                    $submission->article->authors()->create([
                        'name' => $authorItem['name'],
                        'email' => $authorItem['email'],
                        'affiliation' => $authorItem['institution'] ?? 'Academic Scholar',
                        'is_corresponding' => $authorItem['is_corresponding'] ?? 0,
                    ]);
                }

                // Re-issue / refresh certificates with updated authors
                try {
                    CertificateIssuanceService::issueForArticle($submission->article);
                } catch (\Throwable $e) {
                    Log::warning('Certificate sync error on authors update: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'Authors and scholar details updated successfully.');
        }

        $request->validate([
            'status' => 'required|in:submitted,under_review,revision_requested,accepted,rejected,published',
            'revision_comments' => 'nullable|string',
        ]);

        $oldStatus = $submission->status;
        $updateData = ['status' => $request->status];
        if ($request->filled('revision_comments')) {
            $updateData['revision_comments'] = $request->revision_comments;
        }

        $submission->update($updateData);

        // Automatically issue certificates for author and co-authors when status becomes accepted or published
        if (in_array($request->status, ['accepted', 'published'])) {
            try {
                CertificateIssuanceService::issueForSubmission($submission);
            } catch (\Throwable $e) {
                Log::warning('Automatic certificate issuance error on submission update: ' . $e->getMessage());
            }
        }

        // Send information email to the user
        $this->notifyAuthor($submission, 'status_change', [
            'old_status' => $oldStatus,
            'new_status' => $submission->status,
            'comments' => $request->revision_comments,
        ]);

        $authorEmail = $submission->user->email ?? 'author';
        return back()->with('success', "Submission status updated successfully. Information email sent to {$authorEmail}.");
    }

    /**
     * Step 1: Assign Editor and/or Reviewer to the submission.
     */
    public function assign(Request $request, Submission $submission)
    {
        $request->validate([
            'editor_id' => 'nullable|exists:users,id',
            'reviewer_id' => 'nullable|exists:users,id',
            'role_type' => 'nullable|in:reviewer,editor',
        ]);

        if ($request->filled('editor_id')) {
            $submission->editor_id = $request->editor_id;
            $submission->save();
        }

        if ($request->filled('reviewer_id')) {
            $exists = Review::where('submission_id', $submission->id)
                ->where('reviewer_id', $request->reviewer_id)
                ->exists();

            if (!$exists) {
                Review::create([
                    'submission_id' => $submission->id,
                    'reviewer_id' => $request->reviewer_id,
                    'role_type' => $request->role_type ?? 'reviewer',
                ]);
            }
        }

        if ($submission->status === 'submitted') {
            $submission->update(['status' => 'under_review']);
            $this->notifyAuthor($submission, 'assignment', [
                'new_status' => 'under_review',
            ]);
        }

        return back()->with('success', 'Editor / Reviewer assignment updated successfully.');
    }

    /**
     * Step 3: Paper Decision (Accept / Revision Request / Reject)
     */
    public function decision(Request $request, Submission $submission)
    {
        $request->validate([
            'decision' => 'required|in:accepted,revision_requested,rejected',
            'comments' => 'nullable|string',
        ]);

        $submission->status = $request->decision;
        if ($request->filled('comments')) {
            $submission->revision_comments = $request->comments;
        }
        $submission->save();

        // Send notification email to the author
        $this->notifyAuthor($submission, 'decision', [
            'decision' => $request->decision,
            'comments' => $request->comments,
        ]);

        $authorEmail = $submission->user->email ?? 'author';
        $message = match($request->decision) {
            'accepted' => "Paper approved! An acceptance email has been sent to {$authorEmail} with instructions to complete fee payment.",
            'revision_requested' => "Revision requested. An email with reviewer remarks has been sent to {$authorEmail} to submit corrections.",
            'rejected' => "Paper rejected. A decision notice email has been sent to {$authorEmail}.",
        };

        return back()->with('success', $message);
    }

    /**
     * Step 5: Verify Fee Payment
     */
    public function verifyPayment(Request $request, Submission $submission)
    {
        $request->validate([
            'payment_status' => 'required|in:verified,rejected,pending_verification',
        ]);

        $submission->payment_status = $request->payment_status;
        $submission->save();

        // Send payment update email to author
        $this->notifyAuthor($submission, 'payment', [
            'payment_status' => $request->payment_status,
        ]);

        $authorEmail = $submission->user->email ?? 'author';
        return back()->with('success', 'Fee payment verification updated: ' . ucfirst($request->payment_status) . ". Notification email sent to {$authorEmail}.");
    }

    /**
     * Step 6: Schedule Conference Link & Presentation Time
     */
    public function schedulePresentation(Request $request, Submission $submission)
    {
        $request->validate([
            'conference_link' => 'required|url',
            'presentation_day' => 'required|string|max:100',
            'presentation_time' => 'required|string|max:100',
        ]);

        $submission->conference_link = $request->conference_link;
        $submission->presentation_day = $request->presentation_day;
        $submission->presentation_time = $request->presentation_time;
        $submission->save();

        // Send schedule email with conference meeting link to author
        $this->notifyAuthor($submission, 'presentation_schedule', [
            'conference_link' => $request->conference_link,
            'presentation_day' => $request->presentation_day,
            'presentation_time' => $request->presentation_time,
        ]);

        $authorEmail = $submission->user->email ?? 'author';
        return back()->with('success', "Conference meeting link and presentation schedule saved. Schedule email sent to {$authorEmail}.");
    }

    /**
     * Step 7: Mark Participant Attendance & Presentation Status
     */
    public function markAttendance(Request $request, Submission $submission)
    {
        $request->validate([
            'attendance_status' => 'required|in:present,absent,not_marked',
            'presentation_status' => 'required|in:presented,pending,no_show',
        ]);

        $submission->attendance_status = $request->attendance_status;
        $submission->presentation_status = $request->presentation_status;
        
        // Auto generate certificate codes if present
        if ($request->attendance_status === 'present' && empty($submission->certificate_attendee_code)) {
            $submission->certificate_attendee_code = 'CERT-ATT-' . strtoupper(Str::random(8));
        }

        if ($request->presentation_status === 'presented' && empty($submission->certificate_presentation_code)) {
            $submission->certificate_presentation_code = 'CERT-PRES-' . strtoupper(Str::random(8));
        }

        $submission->save();

        // Send attendance & certificate email to author
        $this->notifyAuthor($submission, 'attendance', [
            'attendance_status' => $request->attendance_status,
            'presentation_status' => $request->presentation_status,
        ]);

        $authorEmail = $submission->user->email ?? 'author';
        return back()->with('success', "Attendance & presentation status marked successfully. Notification email sent to {$authorEmail}.");
    }

    /**
     * Step 8: Generate Certificate Codes / Issue Official Publication Certificate
     */
    public function generateCertificate(Request $request, Submission $submission)
    {
        $type = $request->type ?? (empty($submission->conference_id) ? 'publication' : 'presentation');

        if ($type === 'publication' || empty($submission->conference_id) || $submission->article_id || in_array($submission->status, ['accepted', 'published'])) {
            CertificateIssuanceService::ensureTableExists();
            if ($submission->article) {
                CertificateIssuanceService::issueForArticle($submission->article);
            } else {
                CertificateIssuanceService::issueForSubmission($submission);
            }

            $this->notifyAuthor($submission, 'certificate', [
                'type' => 'publication',
            ]);

            return redirect()->route('certificates.publication.submission', $submission)
                ->with('success', 'Official Certificate of Publication generated successfully!');
        }

        if ($type === 'attendee' && empty($submission->certificate_attendee_code)) {
            $submission->certificate_attendee_code = 'CERT-ATT-' . strtoupper(Str::random(8));
        } elseif ($type === 'presentation' && empty($submission->certificate_presentation_code)) {
            $submission->certificate_presentation_code = 'CERT-PRES-' . strtoupper(Str::random(8));
        }

        $submission->save();

        // Send certificate code notification to author
        $this->notifyAuthor($submission, 'certificate', [
            'type' => $type,
        ]);

        $authorEmail = $submission->user->email ?? 'author';
        return back()->with('success', ucfirst($type) . " certificate issued. Notification email sent to {$authorEmail}.");
    }

    /**
     * Download the submission manuscript.
     */
    public function download(Submission $submission)
    {
        if ($submission->binary_content) {
            $filename = $submission->filename ?? 'manuscript_' . $submission->id . '.' . ($submission->extension ?? 'docx');
            return response($submission->binary_content)
                ->header('Content-Type', $submission->mime_type ?? 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        $filePath = $submission->file_path;
        $fullPath = storage_path('app/' . $filePath);

        if (!$filePath || !file_exists($fullPath)) {
            $sampleDocx = storage_path('app/samples/sample.docx');
            $samplePdf = storage_path('app/samples/sample.pdf');
            $extension = strtolower(pathinfo($filePath ?? '', PATHINFO_EXTENSION));
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
        $binaryData = $submission->binary_content;

        if (!$binaryData) {
            $filePath = $submission->file_path;
            $fullPath = storage_path('app/' . $filePath);

            if ($filePath && file_exists($fullPath)) {
                $binaryData = file_get_contents($fullPath);
            } else {
                abort(404, 'Scholarly document not found.');
            }
        }

        $contentType = $submission->mime_type ?? 'application/pdf';
        $filename = $submission->filename ?? 'manuscript_' . $submission->id;

        return response($binaryData)
            ->header('Content-Type', $contentType)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Content-Length', strlen($binaryData))
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Cache-Control', 'private, max-age=3600');
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();
        return redirect()->route('admin.submissions.index')->with('success', 'Submission deleted.');
    }
}
