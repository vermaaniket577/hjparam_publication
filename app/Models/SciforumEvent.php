<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SciforumEvent extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'type',
        'banner_image',
        'submission_link'
    ];
}
