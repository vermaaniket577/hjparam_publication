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
     * View a dynamic, fully populated publication certificate by Certificate model or ID.
     */
    public function show(Certificate $certificate)
    {
        CertificateIssuanceService::ensureTableExists();
        $certificate->loadMissing(['submission.journal', 'article.journal']);
        return view('certificates.publication', compact('certificate'));
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
            return view('certificates.verify', [
                'certificate' => $certificate,
                'submission' => $certificate->submission,
                'code' => $code,
                'isPublication' => true,
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
