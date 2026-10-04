<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function getReadAtAttribute()
    {
        return $this->is_read ? $this->updated_at : null;
    }

    public function setReadAtAttribute($value)
    {
        $this->attributes['is_read'] = (bool) $value;
    }
}
