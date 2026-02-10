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
        'impact_factor',
        'aims_and_scope',
        'is_active',
        'topic_id',
    ];

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
