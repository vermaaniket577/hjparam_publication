<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'issue_id',
        'submission_id',
        'title',
        'slug',
        'abstract',
        'keywords',
        'doi',
        'pdf_path',
        'views_count',
        'downloads_count',
        'status',
        'submitted_at',
        'accepted_at',
        'published_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'accepted_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class);
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function authors()
    {
        return $this->hasMany(Author::class);
    }

    public function getApaCitationAttribute()
    {
        $year = $this->published_at ? $this->published_at->format('Y') : date('Y');
        $authors = $this->authors->pluck('name')->map(function ($name) {
            $parts = explode(' ', $name);
            $last = array_pop($parts);
            $initials = array_map(fn($n) => substr($n, 0, 1) . '.', $parts);
            return $last . ', ' . implode(' ', $initials);
        })->join(', ', ' & ');

        return "{$authors} ({$year}). {$this->title}. {$this->journal->title}, {$this->issue->volume->volume_number}({$this->issue->issue_number}). https://doi.org/{$this->doi}";
    }

    public function getMlaCitationAttribute()
    {
        $authors = $this->authors->pluck('name')->map(function ($name) {
            $parts = explode(' ', $name);
            $last = array_pop($parts);
            return $last . ', ' . implode(' ', $parts);
        })->join(', and ');

        $year = $this->published_at ? $this->published_at->format('Y') : date('Y');
        return "{$authors}. \"{$this->title}.\" {$this->journal->title}, vol. {$this->issue->volume->volume_number}, no. {$this->issue->issue_number}, {$year}. doi:{$this->doi}";
    }

    public function getBibtexAttribute()
    {
        $id = str_replace([' ', '/', '.'], '_', strtolower($this->title));
        $id = substr($id, 0, 20) . '_' . date('Y');
        $authors = $this->authors->pluck('name')->join(' and ');
        $year = $this->published_at ? $this->published_at->format('Y') : date('Y');

        return "@article{{$id},
  title={{$this->title}},
  author={{$authors}},
  journal={{$this->journal->title}},
  volume={{$this->issue->volume->volume_number}},
  number={{$this->issue->issue_number}},
  year={{$year}},
  doi={{$this->doi}},
  publisher={HJPARAM Publication}
}";
    }
}
