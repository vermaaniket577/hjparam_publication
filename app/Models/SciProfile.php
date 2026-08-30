<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SciProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'affiliations',
        'orcid',
        'profile_photo',
        'google_scholar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A SciProfile represents a researcher who has articles, preprints, etc.
    // These are linked via the user_id.

    public function articles()
    {
        return $this->hasManyThrough(
            Article::class,
            Submission::class,
            'user_id', // Foreign key on submissions table...
            'submission_id', // Foreign key on articles table...
            'user_id', // Local key on sci_profiles table...
            'id' // Local key on submissions table...
        );
    }

    public function preprints()
    {
        return $this->hasMany(Preprint::class, 'user_id', 'user_id');
    }

    public function encyclopediaEntries()
    {
        return $this->hasMany(EncyclopediaEntry::class, 'user_id', 'user_id');
    }
}
