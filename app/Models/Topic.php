<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'active', 'sort_order'];

    protected $casts = [
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
