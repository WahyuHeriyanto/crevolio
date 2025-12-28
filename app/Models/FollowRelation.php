<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowRelation extends Model
{
    use SoftDeletes;
    protected $table = 'follow_relations';

    protected $fillable = ['user_id', 'follow_user_id'];
}