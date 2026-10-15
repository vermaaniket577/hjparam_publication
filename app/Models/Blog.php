<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'summary',
        'tags',
        'content',
        'featured_image',
        'author_id',
        'author_name',
        'is_published',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Author relation
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope only published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Dynamic Image URL accessor
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->featured_image)) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        return asset('storage/' . ltrim($this->featured_image, '/'));
    }

    /**
     * Estimate reading time in minutes
     */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content ?? ''));
        return max(1, (int) ceil($words / 200));
    }

    /**
     * Get display author name
     */
    public function getAuthorDisplayNameAttribute(): string
    {
        if (!empty($this->author_name)) {
            return $this->author_name;
        }

        return $this->author?->name ?? 'HJPARAM Editorial';
    }

    /**
     * Return tags as an array
     */
    public function getTagsListAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->tags))));
    }
}
