<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'editor_id',
        'journal_id',
        'conference_id',
        'title',
        'article_type',
        'abstract',
        'keywords',
        'scholars_data',
        'authors_data',
        'file_path',
        'status',
        'revision_comments',
        'payment_status',
        'payment_fee_type',
        'payment_amount',
        'payment_transaction_id',
        'payment_receipt_path',
        'payment_date',
        'conference_link',
        'presentation_day',
        'presentation_time',
        'attendance_status',
        'presentation_status',
        'certificate_attendee_code',
        'certificate_presentation_code',
        'filename',
        'extension',
        'mime_type',
        'file_size',
        'binary_content',
    ];

    protected $casts = [
        'scholars_data' => 'array',
        'authors_data' => 'array',
        'payment_date' => 'date',
    ];

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . ($units[$i] ?? 'B');
    }

    public function getPaymentReceiptUrlAttribute(): ?string
    {
        if (empty($this->payment_receipt_path)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->payment_receipt_path));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }

    public function article()
    {
        return $this->hasOne(Article::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
