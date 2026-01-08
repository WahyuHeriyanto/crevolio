<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'last_seen_at'=> 'datetime',
    ];

    public function isOnline(): bool
    {
        if (! $this->last_seen_at) {
            return false;
        }

        return Carbon::parse($this->last_seen_at)
            ->gt(now()->subMinutes(2));
    }

    public function lastSeenForHumans(): ?string
    {
        return $this->last_seen_at
            ? Carbon::parse($this->last_seen_at)->diffForHumans()
            : null;
    }

    public function expertises()
    {
        return $this->hasMany(UserExpertise::class);
    }

    public function tools()
    {
        return $this->hasMany(UserTool::class);
    }

    public function socialMedias()
    {
        return $this->hasMany(UserSocialMedia::class);
    }

    public function careerPosition()
    {
        return $this->belongsTo(CareerPosition::class, 'career_position_id');
    }

}
