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

    /**
     * The attributes that should be hidden for serialization.
     * Raw binary stream content must never be serialized into JSON / arrays.
     */
    protected $hidden = [
        'binary_content',
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

    /**
     * Get human-readable formatted list of authors for display.
     */
    public function getFormattedAuthorsAttribute(): string
    {
        if ($this->article && $this->article->authors && $this->article->authors->isNotEmpty()) {
            return $this->article->authors->pluck('name')->join(', ');
        }

        if (!empty($this->authors_data) && is_array($this->authors_data)) {
            $names = array_filter(array_map(function ($a) {
                return trim($a['name'] ?? (($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? '')));
            }, $this->authors_data));
            if (!empty($names)) {
                return implode(', ', $names);
            }
        }

        if (!empty($this->scholars_data) && is_array($this->scholars_data)) {
            $names = array_filter(array_map(function ($s) {
                return trim(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? ''));
            }, $this->scholars_data));
            if (!empty($names)) {
                return implode(', ', $names);
            }
        }

        return $this->user->name ?? 'Author';
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

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
