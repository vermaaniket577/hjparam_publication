<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'name',
        'email',
        'affiliation',
        'is_corresponding',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
