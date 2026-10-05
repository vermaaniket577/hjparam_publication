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

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
