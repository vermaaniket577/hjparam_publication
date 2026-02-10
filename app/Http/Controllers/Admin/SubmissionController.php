<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Submission::with(['user', 'journal']);

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
        $submission->load(['user', 'journal', 'reviews.reviewer']);
        // fetch potential reviewers: users with role reviewer or editor
        $reviewers = \App\Models\User::whereIn('role', ['reviewer', 'editor', 'admin'])
            ->where('id', '!=', $submission->user_id) // Cannot review own paper
            ->orderBy('name')
            ->get();

        return view('admin.submissions.show', compact('submission', 'reviewers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Submission $submission)
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,accepted,rejected,published',
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
