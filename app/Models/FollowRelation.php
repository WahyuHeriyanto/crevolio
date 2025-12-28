<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowRelation extends Model
{
    protected $table = 'follow_relations';

    protected $fillable = ['user_id', 'follow_user_id'];
}