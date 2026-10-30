<?php

namespace App\Mail;

use App\Models\PaymentSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public PaymentSubmission $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(PaymentSubmission $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $receiptNo = 'REC-' . str_pad($this->payment->id, 6, '0', STR_PAD_LEFT);

        return $this->subject("[HJPARAM] Payment Receipt & Acknowledgment - {$receiptNo}")
            ->view('emails.payment-receipt');
    }
}
