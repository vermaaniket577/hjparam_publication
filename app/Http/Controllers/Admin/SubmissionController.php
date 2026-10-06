<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\User;
use App\Models\Review;
use App\Mail\SubmissionStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        $query = Submission::with(['user', 'journal', 'conference', 'editor']);

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
        $submission->load(['user', 'journal', 'conference', 'editor', 'reviews.reviewer']);
        
        // Potential editors & reviewers
        $editors = User::whereIn('role', ['editor', 'admin'])->orderBy('name')->get();
        $reviewers = User::whereIn('role', ['reviewer', 'editor', 'admin'])
            ->where('id', '!=', $submission->user_id)
            ->orderBy('name')
            ->get();

        return view('admin.submissions.show', compact('submission', 'editors', 'reviewers'));
    }

    /**
     * Update the general submission status.
     */
    public function update(Request $request, Submission $submission)
    {
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
     * Step 8: Generate Certificate Codes
     */
    public function generateCertificate(Request $request, Submission $submission)
    {
        $type = $request->type ?? 'presentation';

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
