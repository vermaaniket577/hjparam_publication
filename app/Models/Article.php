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
        static::saving(function ($article) {
            // Guard against legacy or drifted databases lacking 'user_id' column
            try {
                if (array_key_exists('user_id', $article->attributes) && !Schema::hasColumn('articles', 'user_id')) {
                    unset($article->attributes['user_id']);
                }
            } catch (\Throwable $e) {}
        });
    }

    public static function ensureManuscriptColumns(): void
    {
        try {
            if (Schema::hasTable('articles')) {
                Schema::table('articles', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!Schema::hasColumn('articles', 'user_id')) {
                        $table->unsignedBigInteger('user_id')->nullable()->after('submission_id');
                    }
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

    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->hasMany(Author::class)->orderBy('id', 'asc');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    protected bool $resolvedRealPdfChecked = false;
    protected ?string $resolvedRealPdfCache = null;

    protected bool $resolvedWordManuscriptChecked = false;
    protected ?string $resolvedWordManuscriptCache = null;

    /**
     * Locate a relative or absolute path across all possible storage roots.
     */
    public function locateFileOnDisk(?string $relPath): ?string
    {
        if (empty($relPath)) {
            return null;
        }

        if (file_exists($relPath) && !is_dir($relPath)) {
            return $relPath;
        }

        $candidates = [
            Storage::disk('public')->path($relPath),
            Storage::disk('local')->path($relPath),
            storage_path('app/public/' . $relPath),
            storage_path('app/private/' . $relPath),
            storage_path('app/' . $relPath),
            public_path('storage/' . $relPath),
            public_path($relPath),
        ];

        foreach ($candidates as $cand) {
            if (file_exists($cand) && !is_dir($cand)) {
                return $cand;
            }
        }

        return null;
    }

    /**
     * Check whether a disk file is a genuine PDF via its %PDF- header (within first 1024 bytes per PDF spec).
     */
    public function isPdfFile(?string $path): bool
    {
        if (empty($path) || !file_exists($path) || is_dir($path)) {
            return false;
        }

        $h = @fopen($path, 'rb');
        if (!$h) {
            return false;
        }

        $header = fread($h, 1024);
        fclose($h);

        return $header !== false && str_contains($header, '%PDF-');
    }

    /**
     * Safely read submission binary_content as a string without consuming stream pointers.
     */
    public function getSubmissionBinaryContent(): ?string
    {
        if (empty($this->submission_id) || empty($this->submission?->binary_content)) {
            return null;
        }

        $raw = $this->submission->binary_content;
        if (is_resource($raw)) {
            @rewind($raw);
            $str = stream_get_contents($raw);
            @rewind($raw);
            return $str !== false && $str !== '' ? $str : null;
        }

        $str = (string) $raw;
        return $str !== '' ? $str : null;
    }

    /**
     * Check whether a binary string is a genuine PDF.
     */
    public function isPdfBinary(?string $binStr): bool
    {
        if (empty($binStr)) {
            return false;
        }

        return str_contains(substr($binStr, 0, 1024), '%PDF-');
    }

    /**
     * Resolve the absolute path on disk for an uploaded real PDF file.
     * Prioritizes admin-uploaded article pdf_path, and falls back to submission file_path
     * unless admin has explicitly uploaded a valid Word manuscript override on disk.
     */
    public function resolveRealPdfPath(): ?string
    {
        if ($this->resolvedRealPdfChecked) {
            return $this->resolvedRealPdfCache;
        }

        $this->resolvedRealPdfChecked = true;
        $relPaths = [];

        if (!empty($this->pdf_path)) {
            $relPaths[] = $this->pdf_path;
        }

        $hasExplicitWordOverride = !empty($this->manuscript_path)
            && $this->locateFileOnDisk($this->manuscript_path) !== null;

        if (!$hasExplicitWordOverride && !empty($this->submission_id) && !empty($this->submission?->file_path)) {
            $relPaths[] = $this->submission->file_path;
        }

        foreach (array_unique(array_filter($relPaths)) as $relPath) {
            $found = $this->locateFileOnDisk($relPath);
            if ($found && $this->isPdfFile($found)) {
                $this->resolvedRealPdfCache = $found;
                return $found;
            }
        }

        return null;
    }

    /**
     * Check if this article has a real uploaded PDF (on disk or submission binary_content).
     */
    public function getHasRealPdfAttribute(): bool
    {
        if ($this->resolveRealPdfPath() !== null) {
            return true;
        }

        $hasExplicitWordOverride = !empty($this->manuscript_path)
            && $this->locateFileOnDisk($this->manuscript_path) !== null;

        if (!$hasExplicitWordOverride) {
            $binStr = $this->getSubmissionBinaryContent();
            if ($this->isPdfBinary($binStr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve the absolute path on disk for a non-PDF Word manuscript (.doc / .docx).
     */
    public function resolveWordManuscriptDiskPath(): ?string
    {
        if ($this->resolvedWordManuscriptChecked) {
            return $this->resolvedWordManuscriptCache;
        }

        $this->resolvedWordManuscriptChecked = true;
        $relPaths = [];

        if (!empty($this->manuscript_path)) {
            $relPaths[] = $this->manuscript_path;
        }

        // Fall back to submission file_path whenever no real PDF exists on disk
        if ($this->resolveRealPdfPath() === null && !empty($this->submission_id) && !empty($this->submission?->file_path)) {
            $relPaths[] = $this->submission->file_path;
        }

        if (!empty($this->pdf_path)) {
            $ext = strtolower(pathinfo($this->pdf_path, PATHINFO_EXTENSION));
            if (in_array($ext, ['doc', 'docx'])) {
                $relPaths[] = $this->pdf_path;
            }
        }

        foreach (array_unique(array_filter($relPaths)) as $relPath) {
            $found = $this->locateFileOnDisk($relPath);
            if ($found && !$this->isPdfFile($found)) {
                $this->resolvedWordManuscriptCache = $found;
                return $found;
            }
        }

        return null;
    }

    /**
     * Check if this article has an uploaded Word manuscript (.doc / .docx) available.
     */
    public function getHasWordManuscriptAttribute(): bool
    {
        if ($this->resolveWordManuscriptDiskPath() !== null) {
            return true;
        }

        if ($this->resolveRealPdfPath() === null) {
            $binStr = $this->getSubmissionBinaryContent();
            if ($binStr !== null && !$this->isPdfBinary($binStr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this article has an original Word manuscript available (either directly or via submission).
     */
    public function getHasManuscriptAttribute(): bool
    {
        return $this->has_word_manuscript;
    }

    /**
     * Get the primary download format matching the exact file uploaded by admin/author.
     */
    public function getPrimaryDownloadFormatAttribute(): string
    {
        if ($this->has_real_pdf) {
            return 'PDF';
        }

        if ($this->has_word_manuscript) {
            return $this->manuscript_extension;
        }

        return 'PDF';
    }

    /**
     * Format byte count cleanly (e.g. "1.8 MB", "481.5 KB").
     */
    protected function formatBytes(?int $bytes): ?string
    {
        if (!$bytes || $bytes <= 0) {
            return null;
        }

        $val = (float) $bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $val > 1024 && $i < count($units) - 1; $i++) {
            $val /= 1024;
        }
        return round($val, 1) . ' ' . $units[$i];
    }

    /**
     * Get formatted file size for the primary uploaded file.
     */
    public function getPrimaryDownloadSizeFormattedAttribute(): ?string
    {
        if ($this->has_real_pdf) {
            $pdfPath = $this->resolveRealPdfPath();
            if ($pdfPath && file_exists($pdfPath)) {
                return $this->formatBytes((int) filesize($pdfPath));
            }
        }

        if ($this->has_word_manuscript) {
            return $this->manuscript_size_formatted;
        }

        return null;
    }

    /**
     * Get uppercase extension format badge (e.g. DOCX or DOC).
     */
    public function getManuscriptExtensionAttribute(): string
    {
        $diskPath = $this->resolveWordManuscriptDiskPath();
        if ($diskPath) {
            $diskExt = pathinfo($diskPath, PATHINFO_EXTENSION);
            if ($diskExt) {
                return strtoupper($diskExt);
            }
        }
        if (!empty($this->file_extension)) {
            return strtoupper(ltrim($this->file_extension, '.'));
        }
        if (!empty($this->manuscript_path)) {
            $ext = pathinfo($this->manuscript_path, PATHINFO_EXTENSION);
            if ($ext) return strtoupper($ext);
        }
        if (!empty($this->submission_id) && !empty($this->submission?->extension)) {
            return strtoupper(ltrim($this->submission->extension, '.'));
        }
        if (!empty($this->submission_id) && !empty($this->submission?->file_path)) {
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
        if (!empty($this->submission_id) && !empty($this->submission?->filename)) {
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
        $wordPath = $this->resolveWordManuscriptDiskPath();
        if ($wordPath) {
            return $wordPath;
        }

        $relPaths = array_filter([
            $this->manuscript_path,
            !empty($this->submission_id) ? $this->submission?->file_path : null,
            $this->pdf_path,
        ]);

        foreach ($relPaths as $relPath) {
            $found = $this->locateFileOnDisk($relPath);
            if ($found) {
                return $found;
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
        if (!$bytes) {
            $diskPath = $this->resolveWordManuscriptDiskPath();
            if ($diskPath && file_exists($diskPath)) {
                $bytes = filesize($diskPath);
            }
        }
        if (!$bytes && !empty($this->submission_id) && $this->submission?->file_size) {
            $bytes = $this->submission->file_size;
        }
        if (!$bytes && !empty($this->submission_id) && !empty($this->submission?->binary_content)) {
            $bytes = is_resource($this->submission->binary_content)
                ? strlen(stream_get_contents($this->submission->binary_content))
                : strlen((string)$this->submission->binary_content);
        }

        return $this->formatBytes($bytes ? (int) $bytes : null);
    }

    public function getApaCitationAttribute()
    {
        $year = !empty($this->published_at) ? \Carbon\Carbon::parse($this->published_at)->format('Y') : date('Y');
        $authorsList = $this->authors->pluck('name')->filter()->values();
        if ($authorsList->isEmpty()) {
            $fallback = $this->submission?->user?->name ?? 'Author';
            $authorsList = collect([$fallback]);
        }

        $authors = $authorsList->map(function ($name) {
            $name = trim($name);
            $parts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY);
            if (empty($parts) || count($parts) <= 1) {
                return $name;
            }
            $last = array_pop($parts);
            $initials = array_map(function ($n) {
                $char = mb_substr(trim($n), 0, 1, 'UTF-8');
                return $char !== '' ? $char . '.' : '';
            }, $parts);
            $initialsStr = implode(' ', array_filter($initials));
            return $initialsStr ? "{$last}, {$initialsStr}" : $last;
        })->join(', ', ' & ');

        $journalTitle = $this->journal->title ?? 'HJPARAM Publication';
        $vol = $this->issue?->volume?->volume_number ?? '1';
        $iss = $this->issue?->issue_number ?? '1';

        $doiPart = $this->doi ? "https://doi.org/{$this->doi}" : "";
        $citation = "{$authors} ({$year}). {$this->title}. {$journalTitle}, {$vol}({$iss}). {$doiPart}";
        return mb_convert_encoding(trim($citation), 'UTF-8', 'UTF-8');
    }

    public function getMlaCitationAttribute()
    {
        $authorsList = $this->authors->pluck('name')->filter()->values();
        if ($authorsList->isEmpty()) {
            $fallback = $this->submission?->user?->name ?? 'Author';
            $authorsList = collect([$fallback]);
        }

        $authors = $authorsList->map(function ($name) {
            $name = trim($name);
            $parts = preg_split('/\s+/u', $name, -1, PREG_SPLIT_NO_EMPTY);
            if (empty($parts) || count($parts) <= 1) {
                return $name;
            }
            $last = array_pop($parts);
            $firstParts = implode(' ', $parts);
            return $firstParts ? "{$last}, {$firstParts}" : $last;
        })->join(', and ');

        $year = !empty($this->published_at) ? \Carbon\Carbon::parse($this->published_at)->format('Y') : date('Y');
        $journalTitle = $this->journal->title ?? 'HJPARAM Publication';
        $vol = $this->issue?->volume?->volume_number ?? '1';
        $iss = $this->issue?->issue_number ?? '1';

        $doiPart = $this->doi ? "doi:{$this->doi}" : "";
        $citation = "{$authors}. \"{$this->title}.\" {$journalTitle}, vol. {$vol}, no. {$iss}, {$year}. {$doiPart}";
        return mb_convert_encoding(trim($citation), 'UTF-8', 'UTF-8');
    }

    public function getBibtexAttribute()
    {
        $id = str_replace([' ', '/', '.', ':', '?', '"', "'"], '_', mb_strtolower($this->title, 'UTF-8'));
        $id = mb_substr($id, 0, 20, 'UTF-8') . '_' . date('Y');
        $authors = $this->authors->pluck('name')->filter()->join(' and ');
        if (!$authors) {
            $authors = $this->submission?->user?->name ?? 'Author';
        }
        $year = !empty($this->published_at) ? \Carbon\Carbon::parse($this->published_at)->format('Y') : date('Y');
        $journalTitle = $this->journal->title ?? 'HJPARAM Publication';
        $vol = $this->issue?->volume?->volume_number ?? '1';
        $iss = $this->issue?->issue_number ?? '1';

        $bibtex = "@article{{$id},
  title={{$this->title}},
  author={{$authors}},
  journal={{$journalTitle}},
  volume={{$vol}},
  number={{$iss}},
  year={{$year}},
  doi={{$this->doi}},
  publisher={HJPARAM Publication}
}";
        return mb_convert_encoding($bibtex, 'UTF-8', 'UTF-8');
    }
}
