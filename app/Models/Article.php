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

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function getApaCitationAttribute()
    {
        $year = !empty($this->published_at) ? \Carbon\Carbon::parse($this->published_at)->format('Y') : date('Y');
        $authors = $this->authors->pluck('name')->map(function ($name) {
            $parts = explode(' ', trim($name));
            $last = array_pop($parts);
            $initials = array_map(fn($n) => substr($n, 0, 1) . '.', $parts);
            return $last . ', ' . implode(' ', $initials);
        })->join(', ', ' & ');

        $journalTitle = $this->journal->title ?? 'HJPARAM Publication';
        $vol = $this->issue?->volume?->volume_number ?? '1';
        $iss = $this->issue?->issue_number ?? '1';

        return "{$authors} ({$year}). {$this->title}. {$journalTitle}, {$vol}({$iss}). " . ($this->doi ? "https://doi.org/{$this->doi}" : "");
    }

    public function getMlaCitationAttribute()
    {
        $authors = $this->authors->pluck('name')->map(function ($name) {
            $parts = explode(' ', trim($name));
            $last = array_pop($parts);
            return $last . ', ' . implode(' ', $parts);
        })->join(', and ');

        $year = !empty($this->published_at) ? \Carbon\Carbon::parse($this->published_at)->format('Y') : date('Y');
        $journalTitle = $this->journal->title ?? 'HJPARAM Publication';
        $vol = $this->issue?->volume?->volume_number ?? '1';
        $iss = $this->issue?->issue_number ?? '1';

        return "{$authors}. \"{$this->title}.\" {$journalTitle}, vol. {$vol}, no. {$iss}, {$year}. " . ($this->doi ? "doi:{$this->doi}" : "");
    }

    public function getBibtexAttribute()
    {
        $id = str_replace([' ', '/', '.'], '_', strtolower($this->title));
        $id = substr($id, 0, 20) . '_' . date('Y');
        $authors = $this->authors->pluck('name')->join(' and ');
        $year = !empty($this->published_at) ? \Carbon\Carbon::parse($this->published_at)->format('Y') : date('Y');
        $journalTitle = $this->journal->title ?? 'HJPARAM Publication';
        $vol = $this->issue?->volume?->volume_number ?? '1';
        $iss = $this->issue?->issue_number ?? '1';

        return "@article{{$id},
  title={{$this->title}},
  author={{$authors}},
  journal={{$journalTitle}},
  volume={{$vol}},
  number={{$iss}},
  year={{$year}},
  doi={{$this->doi}},
  publisher={HJPARAM Publication}
}";
    }
}
