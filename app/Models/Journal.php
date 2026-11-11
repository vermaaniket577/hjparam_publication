<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'issn',
        'format',
        'frequency',
        'subject',
        'language',
        'starting_year',
        'publisher',
        'impact_factor',
        'aims_and_scope',
        'editorial_responsibilities',
        'is_active',
        'topic_id',
    ];

    /**
     * Get effective subject domain.
     */
    public function getEffectiveSubjectAttribute(): string
    {
        return $this->subject ?: ($this->topic ? $this->topic->name : 'Multidisciplinary Research & Academic Studies');
    }

    /**
     * Get effective format.
     */
    public function getEffectiveFormatAttribute(): string
    {
        return $this->format ?: 'Online / Electronic (e-Journal)';
    }

    /**
     * Get effective frequency.
     */
    public function getEffectiveFrequencyAttribute(): string
    {
        return $this->frequency ?: 'Monthly';
    }

    /**
     * Get effective language.
     */
    public function getEffectiveLanguageAttribute(): string
    {
        return $this->language ?: 'English, Hindi';
    }

    /**
     * Get effective starting year.
     */
    public function getEffectiveStartingYearAttribute(): int
    {
        return $this->starting_year ?: 2024;
    }

    /**
     * Get effective publisher.
     */
    public function getEffectivePublisherAttribute(): string
    {
        return $this->publisher ?: 'HJPARAM Publication';
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function volumes()
    {
        return $this->hasMany(Volume::class);
    }

    public function issues()
    {
        return $this->hasManyThrough(Issue::class, Volume::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function editorialBoard()
    {
        return $this->hasMany(EditorialBoard::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
