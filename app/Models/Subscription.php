<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['email', 'interests', 'active'];

    protected $casts = [
        'interests' => 'array',
        'active' => 'boolean',
    ];
}
