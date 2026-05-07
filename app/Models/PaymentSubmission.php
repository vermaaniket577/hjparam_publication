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
        'payment_amount',
        'payment_method',
        'transaction_id',
        'screenshot_path',
        'screenshot_original_name',
        'status',
        'reviewed_at',
        'reviewed_by',
        'admin_note',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
