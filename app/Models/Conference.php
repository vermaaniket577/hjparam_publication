<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'start_date', 'end_date', 'venue', 'city', 
        'country_id', 'category_id', 'organizer_id', 'organizer_name', 'external_link', 
        'banner_image', 'type', 'status', 'is_featured', 'early_bird_deadline', 'invitation_letter_support'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'early_bird_deadline' => 'datetime',
        'is_featured' => 'boolean',
        'invitation_letter_support' => 'boolean',
    ];

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
}
