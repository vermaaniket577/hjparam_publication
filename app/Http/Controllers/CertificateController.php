<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display printable/downloadable Attendee Certificate.
     */
    public function attendee(Submission $submission)
    {
        $submission->load(['conference', 'user']);

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
        $submission->load(['conference', 'user']);

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
        $submission = Submission::where('certificate_attendee_code', $code)
            ->orWhere('certificate_presentation_code', $code)
            ->with(['conference', 'user'])
            ->first();

        $isAttendee = ($submission && $submission->certificate_attendee_code === $code);
        $isPresentation = ($submission && $submission->certificate_presentation_code === $code);

        return view('certificates.verify', compact('submission', 'code', 'isAttendee', 'isPresentation'));
    }
}
