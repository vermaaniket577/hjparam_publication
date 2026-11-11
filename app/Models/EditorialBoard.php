<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorialBoard extends Model
{
    use HasFactory;

    protected $fillable = [
        'editor_uid',
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
        'roles_array',
        'journal_roles_map',
        'all_appointments_summary',
    ];

    /**
     * Get array of all roles assigned to this member.
     */
    public function getRolesArrayAttribute(): array
    {
        if (empty($this->role)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->role))));
    }

    /**
     * Get all appointment records for this editor across all journals.
     */
    public function getAppointmentsAttribute()
    {
        if (!empty($this->editor_uid)) {
            return self::where('editor_uid', $this->editor_uid)->with('journal')->orderBy('id')->get();
        }

        if (!empty($this->email)) {
            return self::where('email', $this->email)->with('journal')->orderBy('id')->get();
        }

        return collect([$this]);
    }

    /**
     * Get a map of journal_id => [roles] for this editor.
     * e.g. ['global' => ['Advisory Board Member'], '1' => ['Editor-in-Chief', 'Section Editor']]
     */
    public function getJournalRolesMapAttribute(): array
    {
        $map = [];

        // If appointments relationship is already loaded or fetch it
        $records = $this->appointments;

        foreach ($records as $rec) {
            $key = $rec->journal_id ? (string) $rec->journal_id : 'global';
            $roles = $rec->roles_array;
            $map[$key] = count($roles) > 0 ? $roles : ['Editorial Board Member'];
        }

        // If map is still empty, include current record
        if (empty($map)) {
            $key = $this->journal_id ? (string) $this->journal_id : 'global';
            $roles = $this->roles_array;
            $map[$key] = count($roles) > 0 ? $roles : ['Editorial Board Member'];
        }

        return $map;
    }

    /**
     * Get a formatted summary of all assigned journals & roles for display.
     */
    public function getAllAppointmentsSummaryAttribute(): array
    {
        $records = $this->appointments;
        $summary = [];

        foreach ($records as $rec) {
            $journalTitle = $rec->journal ? $rec->journal->title : 'Publisher-wide / Central';
            $summary[] = [
                'record_id' => $rec->id,
                'journal_id' => $rec->journal_id,
                'journal_key' => $rec->journal_id ? (string) $rec->journal_id : 'global',
                'journal_title' => $journalTitle,
                'roles' => $rec->roles_array,
                'role_string' => $rec->role,
            ];
        }

        return $summary;
    }

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
