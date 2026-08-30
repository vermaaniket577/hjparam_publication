<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSubmission extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'full_name',
        'email',
        'mobile_number',
        'affiliation',
        'paper_title',
        'fee_type',
        'payment_amount',
        'currency',
        'payment_method',
        'transaction_id',
        'gateway_order_id',
        'gateway_payment_id',
        'gateway_signature',
        'gateway_response',
        'screenshot_path',
        'screenshot_original_name',
        'status',
        'reviewed_at',
        'reviewed_by',
        'admin_note',
    ];

    public function getFeeTypeLabelAttribute(): string
    {
        return match (strtolower($this->fee_type ?? 'journal')) {
            'conference' => 'Conference Fees',
            default => 'Journal Fees',
        };
    }

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getCurrencySymbolAttribute(): string
    {
        return match (strtoupper($this->currency ?? 'INR')) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'AED' => 'AED ',
            default => '₹',
        };
    }

    public function isOnline(): bool
    {
        return in_array($this->payment_method, ['Online Gateway', 'Razorpay', 'PhonePe', 'Stripe', 'PayPal', 'Online / Card / UPI']);
    }
}
