<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'volume_id',
        'issue_number',
        'publication_date',
        'special_issue_title',
        'is_published',
    ];

    public function volume()
    {
        return $this->belongsTo(Volume::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
