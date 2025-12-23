<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTool extends Model
{
    protected $fillable = [
        'user_profile_id',
        'tool_id',
        'custom_tool'
    ];
}
