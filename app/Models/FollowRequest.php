<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowRequest extends Model
{
    protected $table = 'follow_requests';

    protected $fillable = ['user_id', 'requester_id'];
}