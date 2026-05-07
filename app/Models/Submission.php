<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'journal_id',
        'issue_id',
        'title',
        'abstract',
        'keywords',
        'file_path',
        'status',
        'filename',
        'extension',
        'mime_type',
        'file_size',
        'binary_content',
    ];

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++)
            $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function article()
    {
        return $this->hasOne(Article::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
