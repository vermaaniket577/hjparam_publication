<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preprint extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'abstract',
        'file_path',
        'version',
        'doi',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
