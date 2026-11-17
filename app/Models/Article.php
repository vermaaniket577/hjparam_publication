<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'issue_id',
        'submission_id',
        'user_id',
        'title',
        'slug',
        'abstract',
        'keywords',
        'doi',
        'pdf_path',
        'manuscript_path',
        'original_filename',
        'file_extension',
        'file_size',
        'mime_type',
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
        'file_size' => 'integer',
    ];

    protected static function booted()
    {
        static::retrieved(function ($article) {
            // Lazy safeguard for legacy environments
        });
    }

    public static function ensureManuscriptColumns(): void
    {
        try {
            if (Schema::hasTable('articles') && !Schema::hasColumn('articles', 'manuscript_path')) {
                Schema::table('articles', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!Schema::hasColumn('articles', 'manuscript_path')) {
                        $table->string('manuscript_path')->nullable()->after('pdf_path');
                    }
                    if (!Schema::hasColumn('articles', 'original_filename')) {
                        $table->string('original_filename')->nullable()->after('manuscript_path');
                    }
                    if (!Schema::hasColumn('articles', 'file_extension')) {
                        $table->string('file_extension', 16)->nullable()->after('original_filename');
                    }
                    if (!Schema::hasColumn('articles', 'file_size')) {
                        $table->unsignedBigInteger('file_size')->nullable()->after('file_extension');
                    }
                    if (!Schema::hasColumn('articles', 'mime_type')) {
                        $table->string('mime_type')->nullable()->after('file_size');
                    }
                });
            }
        } catch (\Throwable $e) {
            // Silently continue if locked or restricted
        }
    }

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

    /**
     * Check if this article has an original manuscript available (either directly or via submission).
     */
    public function getHasManuscriptAttribute(): bool
    {
        if (!empty($this->manuscript_path)) {
            return true;
        }
        if ($this->submission && (!empty($this->submission->file_path) || !empty($this->submission->binary_content))) {
            return true;
        }
        if (!empty($this->pdf_path)) {
            $ext = strtolower(pathinfo($this->pdf_path, PATHINFO_EXTENSION));
            if (in_array($ext, ['doc', 'docx'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get uppercase extension format badge (e.g. DOCX or DOC).
     */
    public function getManuscriptExtensionAttribute(): string
    {
        if (!empty($this->file_extension)) {
            return strtoupper(ltrim($this->file_extension, '.'));
        }
        if (!empty($this->submission?->extension)) {
            return strtoupper(ltrim($this->submission->extension, '.'));
        }
        if (!empty($this->manuscript_path)) {
            $ext = pathinfo($this->manuscript_path, PATHINFO_EXTENSION);
            if ($ext) return strtoupper($ext);
        }
        if (!empty($this->submission?->file_path)) {
            $ext = pathinfo($this->submission->file_path, PATHINFO_EXTENSION);
            if ($ext) return strtoupper($ext);
        }
        return 'DOCX';
    }

    /**
     * Get clean human-readable original file name.
     */
    public function getManuscriptFilenameAttribute(): string
    {
        if (!empty($this->original_filename)) {
            return $this->original_filename;
        }
        if (!empty($this->submission?->filename)) {
            return $this->submission->filename;
        }
        $ext = strtolower($this->manuscript_extension ?: 'docx');
        return Str::slug($this->title) . '.' . $ext;
    }

    /**
     * Resolve the absolute path on disk for this manuscript across potential storage locations.
     */
    public function resolveManuscriptDiskPath(): ?string
    {
        $relPaths = array_filter([
            $this->manuscript_path,
            $this->submission?->file_path,
            $this->pdf_path,
        ]);

        foreach ($relPaths as $relPath) {
            if (empty($relPath)) continue;

            if (file_exists($relPath) && !is_dir($relPath)) {
                return $relPath;
            }

            $candidates = [
                Storage::disk('local')->path($relPath),
                storage_path('app/private/' . $relPath),
                storage_path('app/' . $relPath),
                Storage::disk('public')->path($relPath),
                storage_path('app/public/' . $relPath),
                public_path('storage/' . $relPath),
                public_path($relPath),
            ];

            foreach ($candidates as $cand) {
                if (file_exists($cand) && !is_dir($cand)) {
                    return $cand;
                }
            }
        }

        return null;
    }

    /**
     * Format the manuscript file size (e.g. "1.8 MB", "420 KB").
     */
    public function getManuscriptSizeFormattedAttribute(): ?string
    {
        $bytes = $this->file_size;
        if (!$bytes && $this->submission?->file_size) {
            $bytes = $this->submission->file_size;
        }
        if (!$bytes) {
            $diskPath = $this->resolveManuscriptDiskPath();
            if ($diskPath && file_exists($diskPath)) {
                $bytes = filesize($diskPath);
            }
        }
        if (!$bytes && !empty($this->submission?->binary_content)) {
            $bytes = is_resource($this->submission->binary_content)
                ? strlen(stream_get_contents($this->submission->binary_content))
                : strlen((string)$this->submission->binary_content);
        }

        if (!$bytes) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 1) . ' ' . $units[$i];
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
