<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference_id',
        'name',
        'email',
        'mobile',
        'message',
        'status',
    ];

    public function conference()
    {
        return $this->belongsTo(Conference::class);
    }
}
