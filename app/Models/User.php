<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'affiliation',
        'phone',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    public function isSubAdmin()
    {
        return $this->role === 'sub_admin';
    }

    public function isEditor()
    {
        return in_array($this->role, ['editor', 'admin', 'super_admin', 'sub_admin']);
    }

    public function isReviewer()
    {
        return in_array($this->role, ['reviewer', 'editor', 'admin', 'super_admin', 'sub_admin']);
    }

    public function isAuthor()
    {
        return $this->role === 'author';
    }

    public function sciProfile()
    {
        return $this->hasOne(SciProfile::class);
    }

    public function preprints()
    {
        return $this->hasMany(Preprint::class);
    }

    public function encyclopediaEntries()
    {
        return $this->hasMany(EncyclopediaEntry::class);
    }
}
