<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowRequest extends Model
{
    use SoftDeletes;
    protected $table = 'follow_requests';

    protected $fillable = ['user_id', 'requester_id'];
}