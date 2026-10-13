<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorialBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'name',
        'affiliation',
        'email',
        'role',
        'bio',
        'photo',
        'sort_order',
    ];

    protected $appends = [
        'photo_url',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    /**
     * Get the accessible public URL for the member's photo.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (empty($this->photo)) {
            return null;
        }

        $photo = str_replace('\\', '/', trim($this->photo));

        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }

        $cleanPath = ltrim($photo, '/');

        if (str_starts_with($cleanPath, 'storage/app/public/')) {
            return asset($cleanPath);
        }

        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        // On live shared hosting where public/storage symlink is missing, files are located at /storage/app/public/
        $host = request()?->getHost() ?? '';
        $isLocal = str_contains($host, '127.0.0.1') || str_contains($host, 'localhost');

        if (!$isLocal) {
            // Check if standard symlink actually works/exists in public
            if (file_exists(public_path('storage/' . $cleanPath))) {
                return asset('storage/' . $cleanPath);
            }
            return asset('storage/app/public/' . $cleanPath);
        }

        // Local environment: check public/storage then storage/app/public
        if (file_exists(public_path('storage/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        if (file_exists(storage_path('app/public/' . $cleanPath))) {
            return asset('storage/app/public/' . $cleanPath);
        }

        return asset('storage/' . $cleanPath);
    }
}
