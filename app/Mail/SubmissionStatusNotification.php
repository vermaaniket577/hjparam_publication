<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Submission $submission;
    public string $updateType;
    public array $data;
    public string $subjectLine;
    public string $headline;
    public string $badgeText;
    public string $badgeColor;
    public string $messageBody;
    public ?string $remarks = null;
    public string $actionUrl;
    public string $actionText;

    /**
     * Create a new message instance.
     */
    public function __construct(Submission $submission, string $updateType = 'status_change', array $data = [])
    {
        $this->submission = $submission;
        $this->updateType = $updateType;
        $this->data = $data;

        $this->configureDetails();
    }

    /**
     * Configure specific email text, subject, badges, and CTAs according to the update type.
     */
    protected function configureDetails(): void
    {
        $subId = str_pad($this->submission->id, 5, '0', STR_PAD_LEFT);
        $shortTitle = \Illuminate\Support\Str::limit($this->submission->title, 45);

        // Fallback action url
        try {
            $this->actionUrl = route('submission.show', $this->submission->id);
        } catch (\Throwable $e) {
            $this->actionUrl = url('/submissions/' . $this->submission->id);
        }
        $this->actionText = 'View Submission Details';

        switch ($this->updateType) {
            case 'decision':
                $decision = $this->data['decision'] ?? $this->submission->status;
                $this->remarks = $this->data['comments'] ?? $this->submission->revision_comments;

                if ($decision === 'accepted') {
                    $this->subjectLine = "[HJPARAM] Congratulations! Paper Accepted - #SUB-{$subId}";
                    $this->headline = 'Congratulations! Your Manuscript Has Been Accepted';
                    $this->badgeText = 'Accepted';
                    $this->badgeColor = '#059669'; // Emerald
                    $this->messageBody = 'We are pleased to inform you that following thorough peer review and editorial assessment, your paper has been accepted for publication. Please proceed to complete author registration and fee payment to finalize the scheduling.';
                    try {
                        $this->actionUrl = route('submissions.payment', $this->submission->id);
                        $this->actionText = 'Proceed to Payment & Registration';
                    } catch (\Throwable $e) {
                        $this->actionUrl = url('/submissions/' . $this->submission->id . '/payment');
                        $this->actionText = 'Proceed to Payment';
                    }
                } elseif ($decision === 'revision_requested') {
                    $this->subjectLine = "[HJPARAM] Action Required: Revision Requested - #SUB-{$subId}";
                    $this->headline = 'Action Required: Revision Requested for Your Paper';
                    $this->badgeText = 'Revision Requested';
                    $this->badgeColor = '#d97706'; // Amber
                    $this->messageBody = 'The editorial desk and peer reviewers have completed their initial evaluation. A revision is requested before a final publication decision can be made. Please address all reviewer comments and upload your revised manuscript.';
                    try {
                        $this->actionUrl = route('submissions.resubmit', $this->submission->id);
                        $this->actionText = 'Upload Revised Manuscript';
                    } catch (\Throwable $e) {
                        $this->actionUrl = url('/submissions/' . $this->submission->id . '/resubmit');
                        $this->actionText = 'Upload Revision';
                    }
                } else { // rejected
                    $this->subjectLine = "[HJPARAM] Editorial Decision Notice - #SUB-{$subId}";
                    $this->headline = 'Editorial Decision Regarding Your Submission';
                    $this->badgeText = 'Rejected';
                    $this->badgeColor = '#e11d48'; // Rose
                    $this->messageBody = 'Thank you for submitting your scholarly work to HJPARAM. Following editorial and peer evaluation, we regret to inform you that we are unable to accept your manuscript for publication in this volume.';
                    $this->actionText = 'View Review Record';
                }
                break;

            case 'payment':
                $paymentStatus = $this->data['payment_status'] ?? $this->submission->payment_status;
                if ($paymentStatus === 'verified') {
                    $this->subjectLine = "[HJPARAM] Payment Verified - #SUB-{$subId}";
                    $this->headline = 'Registration & Publication Fee Verified';
                    $this->badgeText = 'Fee Verified';
                    $this->badgeColor = '#059669';
                    $this->messageBody = 'Your publication / conference registration fee payment has been successfully verified by our administrative team. Your paper is now officially confirmed for the upcoming schedule.';
                    $this->actionText = 'View Author Desk';
                } elseif ($paymentStatus === 'rejected') {
                    $this->subjectLine = "[HJPARAM] Action Needed: Payment Verification Update - #SUB-{$subId}";
                    $this->headline = 'Payment Proof Could Not Be Verified';
                    $this->badgeText = 'Payment Rejected';
                    $this->badgeColor = '#e11d48';
                    $this->messageBody = 'The administrative desk was unable to verify your submitted payment receipt. Please review your transaction ID, ensure the receipt image/PDF is clearly legible, and re-submit your payment proof.';
                    try {
                        $this->actionUrl = route('submissions.payment', $this->submission->id);
                        $this->actionText = 'Re-upload Payment Proof';
                    } catch (\Throwable $e) {
                        $this->actionUrl = url('/submissions/' . $this->submission->id . '/payment');
                    }
                } else {
                    $this->subjectLine = "[HJPARAM] Payment Received Under Verification - #SUB-{$subId}";
                    $this->headline = 'Payment Proof Received';
                    $this->badgeText = 'Pending Verification';
                    $this->badgeColor = '#2563eb';
                    $this->messageBody = 'Your payment receipt has been received and queued for administrative verification. You will be notified once verification is complete.';
                }
                break;

            case 'presentation_schedule':
                $this->subjectLine = "[HJPARAM] Presentation Schedule Confirmed - #SUB-{$subId}";
                $this->headline = 'Conference Presentation Slot & Meeting Link Confirmed';
                $this->badgeText = 'Presentation Scheduled';
                $this->badgeColor = '#4f46e5'; // Indigo
                $this->messageBody = 'The conference committee has scheduled your presentation time and session details. Please review your scheduled date, time slot, and meeting link below.';
                if (!empty($this->submission->conference_link)) {
                    $this->actionUrl = $this->submission->conference_link;
                    $this->actionText = 'Join Conference Meeting Link';
                }
                break;

            case 'attendance':
            case 'certificate':
                $this->subjectLine = "[HJPARAM] Attendance & Certificate Update - #SUB-{$subId}";
                $this->headline = 'Attendance Marked & Certificate Issued';
                $this->badgeText = 'Certificate Issued';
                $this->badgeColor = '#059669';
                $this->messageBody = 'Your conference attendance and presentation status have been officially verified. Your certificate verification codes have been generated and are now available on your author portal.';
                $this->actionText = 'Access Certificates';
                break;

            case 'assignment':
                $this->subjectLine = "[HJPARAM] Editorial Desk Update - #SUB-{$subId}";
                $this->headline = 'Reviewers & Handling Editor Assigned';
                $this->badgeText = 'Under Review';
                $this->badgeColor = '#0284c7'; // Sky blue
                $this->messageBody = 'An editor and peer reviewers have been formally assigned to your manuscript. Your paper is actively undergoing the peer-review process.';
                $this->actionText = 'Track Review Progress';
                break;

            default: // status_change
                $newStatus = $this->data['new_status'] ?? $this->submission->status;
                $readableStatus = ucfirst(str_replace('_', ' ', $newStatus));
                $this->remarks = $this->data['comments'] ?? $this->submission->revision_comments;

                $this->subjectLine = "[HJPARAM] Status Update ({$readableStatus}) - #SUB-{$subId}";
                $this->headline = "Submission Status Updated: {$readableStatus}";
                $this->badgeText = $readableStatus;

                $this->badgeColor = match ($newStatus) {
                    'accepted', 'published' => '#059669',
                    'revision_requested' => '#d97706',
                    'rejected' => '#e11d48',
                    'under_review' => '#0284c7',
                    default => '#2563eb',
                };

                $this->messageBody = match ($newStatus) {
                    'published' => 'Your manuscript has officially been published! It is now accessible in the journal archives and discovery indexes.',
                    'under_review' => 'Your submission has moved to the active peer-review stage.',
                    'revision_requested' => 'Revisions have been requested on your manuscript. Please see the editorial remarks below.',
                    'accepted' => 'Your paper has been accepted for publication.',
                    'rejected' => 'An editorial decision has been made regarding your submission.',
                    default => "The status of your submission #SUB-{$subId} has been updated to {$readableStatus}.",
                };
                break;
        }
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.submission-status');
    }
}
