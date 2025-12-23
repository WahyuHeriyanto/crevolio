<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExpertise extends Model
{
        protected $fillable = [
        'user_profile_id',
        'expertise_id',
        'custom_expertise'
    ];
}
