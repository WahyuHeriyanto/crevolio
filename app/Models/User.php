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
        'username',
        'slug',
        'email',
        'password',
        'access_level'
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

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function followings() 
    { 
        return $this->hasMany(FollowRelation::class, 'user_id'); 
    }

    public function followers() 
        { return $this->hasMany(FollowRelation::class, 'follow_user_id'); 
    }

    public function followRequestsSent() 
        { return $this->hasMany(FollowRequest::class, 'requester_id'); 
    }
    public function followRequestsReceived() 
        { return $this->hasMany(FollowRequest::class, 'user_id'); 
    }  

    public function saveds()
    {
        return $this->hasMany(ProjectSaved::class, 'user_id');
    }

    public function projectAccesses() 
    {
        return $this->hasMany(ProjectAccess::class, 'access_user_id');
    }
}
