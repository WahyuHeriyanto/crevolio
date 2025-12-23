<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'birth',
        'photo_profile',
        'background_image',
        'short_description',
        'description',
        'career_position_id',
        'status',
        'followers',
        'following',
        'last_seen_at'
    ];

    public function expertises()
    {
        return $this->hasMany(UserExpertise::class);
    }

    public function tools()
    {
        return $this->hasMany(UserTool::class);
    }
}
