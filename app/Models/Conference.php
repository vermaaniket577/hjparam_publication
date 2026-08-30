<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'start_date', 'end_date', 'venue', 'city', 
        'country_id', 'category_id', 'organizer_id', 'organizer_name', 'contact_email', 'contact_phone', 'external_link', 
        'banner_image', 'brochure_file', 'type', 'status', 'is_featured', 'early_bird_deadline', 'invitation_letter_support',
        'aim_scope', 'guidelines', 'paper_format_1', 'paper_format_2',
        'fee_attendee', 'fee_presentation', 'fee_publication', 'fee_extra_certificate',
        'custom_fees', 'committee_members',
        'abstract_submission_start_date', 'abstract_submission_end_date',
        'paper_submission_start_date', 'paper_submission_end_date',
        'registration_start_date', 'registration_end_date',
        'venue_details', 'meeting_link', 'publication_info', 'paper_template', 'sample_certificate',
        'book_publication_title', 'book_publication_author', 'book_publication_abstract', 'book_publication_content',
        'journal_publication_title', 'journal_publication_content'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'early_bird_deadline' => 'datetime',
        'is_featured' => 'boolean',
        'invitation_letter_support' => 'boolean',
        'custom_fees' => 'array',
        'committee_members' => 'array',
        'abstract_submission_start_date' => 'date',
        'abstract_submission_end_date' => 'date',
        'paper_submission_start_date' => 'date',
        'paper_submission_end_date' => 'date',
        'registration_start_date' => 'date',
        'registration_end_date' => 'date',
    ];

    public function getBannerUrlAttribute(): ?string
    {
        if (empty($this->banner_image)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->banner_image));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        if (str_starts_with($cleanPath, 'images/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getBrochureUrlAttribute(): ?string
    {
        if (empty($this->brochure_file)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->brochure_file));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getPaperFormat1UrlAttribute(): ?string
    {
        if (empty($this->paper_format_1)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->paper_format_1));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getPaperFormat2UrlAttribute(): ?string
    {
        if (empty($this->paper_format_2)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->paper_format_2));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getPaperTemplateUrlAttribute(): ?string
    {
        if (empty($this->paper_template)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->paper_template));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getSampleCertificateUrlAttribute(): ?string
    {
        if (empty($this->sample_certificate)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->sample_certificate));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            return asset($cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }

    public function getBannerOrFallbackAttribute(): string
    {
        return $this->banner_url ?: asset('images/conference-hero-bg.jpg');
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function category()
    {
        return $this->belongsTo(Topic::class, 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Topic::class, 'conference_topic', 'conference_id', 'topic_id');
    }

    public function enquiries()
    {
        return $this->hasMany(ConferenceEnquiry::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
