<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'type',
        'submission_id',
        'article_id',
        'user_id',
        'recipient_name',
        'recipient_role',
        'recipient_email',
        'designation',
        'department',
        'institution',
        'city_country',
        'manuscript_id',
        'paper_title',
        'journal_name',
        'volume',
        'issue',
        'month_year',
        'pages',
        'issn',
        'doi',
        'issue_date',
        'verification_hash',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Build the affiliation formatted string.
     */
    public function getFormattedAffiliationAttribute(): string
    {
        $parts = array_filter([
            $this->designation,
            $this->department,
            $this->institution,
            $this->city_country,
        ]);

        return !empty($parts) ? implode(', ', $parts) : ($this->institution ?: 'Research Scholar');
    }
}
