<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'category', 'content', 'meta_title', 'meta_description', 'active', 'sort_order'];


    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
