<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Certificate;
use App\Models\Submission;
use App\Services\CertificateIssuanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CertificateController extends Controller
{
    /**
     * View a dynamic, fully populated certificate (publication or appointment).
     */
    public function show(Certificate $certificate)
    {
        CertificateIssuanceService::ensureTableExists();
        $certificate->loadMissing(['submission.journal', 'article.journal', 'editorialBoard.journal']);
        self::sanitizeCertificate($certificate);

        if ($certificate->isAppointment()) {
            return $this->showAppointment($certificate);
        }

        return view('certificates.publication', compact('certificate'));
    }

    /**
     * View official Certificate of Appointment (Editorial Board / Reviewer).
     */
    public function showAppointment(Certificate $certificate)
    {
        CertificateIssuanceService::ensureTableExists();
        $certificate->loadMissing(['editorialBoard.journal', 'journal']);
        $cert = $certificate;
        $member = $certificate->editorialBoard;
        return view('certificates.appointment', compact('cert', 'member'));
    }

    /**
     * View or auto-issue appointment certificate for an Editorial Board member.
     */
    public function forEditorialMember(\App\Models\EditorialBoard $member)
    {
        CertificateIssuanceService::ensureTableExists();
        $cert = CertificateIssuanceService::issueForEditorialMember($member);
        return view('certificates.appointment', compact('cert', 'member'));
    }

    /**
     * View or auto-issue appointment certificate for a User (Editor / Reviewer).
     */
    public function forUserAppointment(\App\Models\User $user)
    {
        CertificateIssuanceService::ensureTableExists();
        $cert = CertificateIssuanceService::issueForUserAppointment($user);
        $member = $cert->editorialBoard;
        return view('certificates.appointment', compact('cert', 'member'));
    }

    /**
     * Clean up legacy duplicate prefixes (e.g. Volume Volume, Issue Issue, pp. pp.)
     * and sync missing page numbers with linked article.
     */
    public static function sanitizeCertificate(Certificate $certificate): Certificate
    {
        $dirty = false;
        if (!empty($certificate->volume) && preg_match('/^volume\s*/i', $certificate->volume)) {
            $certificate->volume = preg_replace('/^volume\s*/i', '', trim($certificate->volume)) ?: '1';
            $dirty = true;
        }
        if (!empty($certificate->issue) && preg_match('/^issue\s*/i', $certificate->issue)) {
            $certificate->issue = preg_replace('/^issue\s*/i', '', trim($certificate->issue)) ?: '1';
            $dirty = true;
        }
        if (!empty($certificate->pages) && preg_match('/^pp?\.?\s*/i', $certificate->pages)) {
            $certificate->pages = preg_replace('/^pp?\.?\s*/i', '', trim($certificate->pages));
            $dirty = true;
        } elseif (empty($certificate->pages)) {
            $art = $certificate->article ?? ($certificate->submission?->article ?? null);
            if ($art) {
                $certificate->pages = $art->formatted_pages;
                $dirty = true;
            }
        }
        if ($dirty && $certificate->exists) {
            try { $certificate->saveQuietly(); } catch (\Throwable $e) {}
        }
        return $certificate;
    }

    /**
     * Update certificate fields (page numbers, volume, issue, recipient details, etc.)
     * and synchronize back to the linked Article record.
     */
    public function update(Request $request, Certificate $certificate)
    {
        // Auth check: allow admin/editor or the submission/article owner
        $isStaff = Auth::check() && in_array(Auth::user()->role, ['admin', 'editor']);
        $isOwner = Auth::check() && (
            (Auth::id() === $certificate->user_id) || 
            ($certificate->submission && Auth::id() === $certificate->submission->user_id) ||
            ($certificate->article && Auth::id() === $certificate->article->user_id)
        );

        if (!$isStaff && !$isOwner) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        CertificateIssuanceService::ensureTableExists();

        $validated = $request->validate([
            'pages' => 'nullable|string|max:50',
            'volume' => 'nullable|string|max:50',
            'issue' => 'nullable|string|max:50',
            'month_year' => 'nullable|string|max:100',
            'recipient_name' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:500',
            'city_country' => 'nullable|string|max:255',
            'recipient_role' => 'nullable|string|max:255',
            'paper_title' => 'nullable|string|max:500',
            'journal_name' => 'nullable|string|max:255',
            'certificate_number' => 'nullable|string|max:100',
            'issue_date' => 'nullable|date',
            'issn' => 'nullable|string|max:50',
            'doi' => 'nullable|string|max:255',
        ]);

        if ($request->has('pages')) {
            $cleanPages = preg_replace('/^pp?\.?\s*/i', '', trim((string)$request->pages));
            $certificate->pages = $cleanPages;
        }
        if ($request->has('volume')) {
            $cleanVol = preg_replace('/^volume\s*/i', '', trim((string)$request->volume));
            $certificate->volume = $cleanVol;
        }
        if ($request->has('issue')) {
            $cleanIssue = preg_replace('/^issue\s*/i', '', trim((string)$request->issue));
            $certificate->issue = $cleanIssue;
        }
        if ($request->has('month_year')) {
            $certificate->month_year = trim((string)$request->month_year);
        }
        if ($request->has('recipient_name') && !empty($request->recipient_name)) {
            $certificate->recipient_name = trim((string)$request->recipient_name);
        }
        if ($request->has('institution')) {
            $certificate->institution = trim((string)$request->institution);
        }
        if ($request->has('paper_title') && !empty($request->paper_title)) {
            $certificate->paper_title = trim((string)$request->paper_title);
        }
        if ($request->has('journal_name') && !empty($request->journal_name)) {
            $certificate->journal_name = trim((string)$request->journal_name);
        }
        if ($request->has('issn')) {
            $certificate->issn = trim((string)$request->issn);
        }
        if ($request->has('doi')) {
            $certificate->doi = trim((string)$request->doi);
        }
        if ($request->has('designation')) {
            $certificate->designation = trim((string)$request->designation);
        }
        if ($request->has('department')) {
            $certificate->department = trim((string)$request->department);
        }
        if ($request->has('city_country')) {
            $certificate->city_country = trim((string)$request->city_country);
        }
        if ($request->has('recipient_role') && !empty($request->recipient_role)) {
            $certificate->recipient_role = trim((string)$request->recipient_role);
        }
        if ($request->has('certificate_number') && !empty($request->certificate_number)) {
            $certificate->certificate_number = trim((string)$request->certificate_number);
        }
        if ($request->filled('issue_date')) {
            $certificate->issue_date = $request->issue_date;
        }

        $certificate->save();

        // Synchronize with linked Editorial Board member if present
        if ($certificate->editorial_board_id && $certificate->editorialBoard) {
            $member = $certificate->editorialBoard;
            if ($request->has('recipient_name') && !empty($request->recipient_name)) {
                $member->name = $certificate->recipient_name;
            }
            if ($request->has('designation')) {
                $member->designation = $certificate->designation;
            }
            if ($request->has('department')) {
                $member->department = $certificate->department;
            }
            if ($request->has('institution')) {
                $member->affiliation = $certificate->institution;
            }
            if ($request->has('recipient_role') && !empty($request->recipient_role)) {
                $member->role = $certificate->recipient_role;
            }
            $member->save();
        }

        // Synchronize with linked Article if present
        $article = $certificate->article ?? ($certificate->submission?->article ?? null);
        if ($article) {
            Article::ensureManuscriptColumns();
            if ($request->has('pages') && Schema::hasColumn('articles', 'pages')) {
                $article->pages = $certificate->pages;
            }
            if ($request->has('doi') && !empty($certificate->doi)) {
                $article->doi = $certificate->doi;
            }
            $article->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Certificate updated and synchronized successfully!',
                'certificate' => $certificate,
            ]);
        }

        return back()->with('success', 'Certificate updated and saved successfully.');
    }

    /**
     * Show certificates for a published submission or article, auto-generating if not yet created.
     */
    public function forSubmission(Submission $submission, Request $request)
    {
        // Require submission to be accepted or published, or user is admin/editor/owner
        $isOwnerOrStaff = Auth::check() && (Auth::id() === $submission->user_id || in_array(Auth::user()->role, ['admin', 'editor']));
        if (!$isOwnerOrStaff && !in_array($submission->status, ['accepted', 'published'])) {
            abort(403, 'Certificate is available once the manuscript is accepted or published.');
        }

        CertificateIssuanceService::ensureTableExists();

        if (!Schema::hasTable('certificates')) {
            return back()->with('error', 'Certificate service is initializing. Please try again or run migrations.');
        }

        $certs = Certificate::where('submission_id', $submission->id)->get();
        if ($certs->isEmpty() && $submission->article) {
            $certs = Certificate::where('article_id', $submission->article->id)->get();
        }
        if ($certs->isEmpty()) {
            if ($submission->article) {
                $certs = collect(CertificateIssuanceService::issueForArticle($submission->article));
            } else {
                $certs = collect(CertificateIssuanceService::issueForSubmission($submission));
            }
        }

        if ($certs->isEmpty()) {
            return back()->with('error', 'No certificate generated for this submission yet.');
        }

        $certs->each(function ($c) {
            self::sanitizeCertificate($c);
        });

        // If an author index or cert id requested
        if ($request->has('cert_id')) {
            $certificate = $certs->firstWhere('id', $request->cert_id) ?? $certs->first();
            return view('certificates.publication', compact('certificate', 'certs'));
        }

        $certificate = $certs->first();
        if (!$certificate) {
            abort(404, 'No certificate found for this submission.');
        }

        return view('certificates.publication', compact('certificate', 'certs'));
    }

    /**
     * Show certificates for a published Article.
     */
    public function forArticle(Article $article, Request $request)
    {
        $isOwnerOrStaff = Auth::check() && (Auth::id() === $article->user_id || in_array(Auth::user()->role, ['admin', 'editor']));
        if (!$isOwnerOrStaff && $article->status !== 'published') {
            abort(403, 'Certificate is available once the article is published.');
        }

        CertificateIssuanceService::ensureTableExists();

        if (!Schema::hasTable('certificates')) {
            return back()->with('error', 'Certificate service is initializing. Please try again or run migrations.');
        }

        $certs = Certificate::where('article_id', $article->id)->get();
        if ($certs->isEmpty()) {
            $certs = collect(CertificateIssuanceService::issueForArticle($article));
        }

        if ($certs->isEmpty()) {
            return back()->with('error', 'No certificate generated for this article yet.');
        }

        $certs->each(function ($c) {
            self::sanitizeCertificate($c);
        });

        if ($request->has('cert_id')) {
            $certificate = $certs->firstWhere('id', $request->cert_id) ?? $certs->first();
            return view('certificates.publication', compact('certificate', 'certs'));
        }

        $certificate = $certs->first();
        if (!$certificate) {
            abort(404, 'No certificate found for this article.');
        }

        return view('certificates.publication', compact('certificate', 'certs'));
    }

    /**
     * Display printable/downloadable Attendee Certificate.
     */
    public function attendee(Submission $submission)
    {
        $submission->loadMissing(['conference', 'user', 'article']);

        // If this submission has no conference (journal submission), or is published/has an article:
        // it must show the official Publication Certificate (Image 2 format)
        if (empty($submission->conference_id) || $submission->article_id || $submission->journal_id || in_array($submission->status, ['accepted', 'published'])) {
            return $this->forSubmission($submission, request());
        }

        if (!$submission->certificate_attendee_code) {
            $submission->certificate_attendee_code = 'CERT-ATT-' . strtoupper(\Illuminate\Support\Str::random(8));
            $submission->save();
        }

        $scholarName = $submission->user->name;
        if (!empty($submission->scholars_data) && is_array($submission->scholars_data)) {
            $first = $submission->scholars_data[0];
            if (!empty($first['first_name'])) {
                $scholarName = $first['first_name'] . ' ' . ($first['last_name'] ?? '');
            }
        }

        return view('certificates.attendee', compact('submission', 'scholarName'));
    }

    /**
     * Display printable/downloadable Presentation Certificate.
     */
    public function presentation(Submission $submission)
    {
        $submission->loadMissing(['conference', 'user', 'article']);

        // If this submission has no conference (journal submission), or is published/has an article:
        // it must show the official Publication Certificate (Image 2 format)
        if (empty($submission->conference_id) || $submission->article_id || $submission->journal_id || in_array($submission->status, ['accepted', 'published'])) {
            return $this->forSubmission($submission, request());
        }

        if (!$submission->certificate_presentation_code) {
            $submission->certificate_presentation_code = 'CERT-PRES-' . strtoupper(\Illuminate\Support\Str::random(8));
            $submission->save();
        }

        $scholarName = $submission->user->name;
        if (!empty($submission->scholars_data) && is_array($submission->scholars_data)) {
            $first = $submission->scholars_data[0];
            if (!empty($first['first_name'])) {
                $scholarName = $first['first_name'] . ' ' . ($first['last_name'] ?? '');
            }
        }

        return view('certificates.presentation', compact('submission', 'scholarName'));
    }

    /**
     * Public Certificate Verification endpoint.
     */
    public function verify($code)
    {
        CertificateIssuanceService::ensureTableExists();

        // 1. Check in official publication certificates table
        $certificate = null;
        if (Schema::hasTable('certificates')) {
            $certificate = Certificate::where('certificate_number', $code)
                ->orWhere('verification_hash', $code)
                ->with(['submission.journal', 'article.journal'])
                ->first();
        }

        if ($certificate) {
            $isAppointment = $certificate->isAppointment();
            return view('certificates.verify', [
                'certificate' => $certificate,
                'submission' => $certificate->submission,
                'code' => $code,
                'isPublication' => !$isAppointment,
                'isAppointment' => $isAppointment,
                'isAttendee' => false,
                'isPresentation' => false,
            ]);
        }

        // 2. Check in conference submissions codes
        $submission = Submission::where('certificate_attendee_code', $code)
            ->orWhere('certificate_presentation_code', $code)
            ->with(['conference', 'user'])
            ->first();

        $isAttendee = ($submission && $submission->certificate_attendee_code === $code);
        $isPresentation = ($submission && $submission->certificate_presentation_code === $code);

        return view('certificates.verify', [
            'certificate' => null,
            'submission' => $submission,
            'code' => $code,
            'isPublication' => false,
            'isAttendee' => $isAttendee,
            'isPresentation' => $isPresentation,
        ]);
    }
}
