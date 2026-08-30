<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncyclopediaEntry extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'category',
        'version',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
