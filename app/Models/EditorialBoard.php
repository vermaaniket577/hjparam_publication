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
        'role',
        'bio',
        'photo',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
