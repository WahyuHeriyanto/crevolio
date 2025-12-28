<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectLike extends Model
{
    use SoftDeletes;
    protected $table = 'project_likes';

    protected $fillable = ['user_id', 'project_id'];
}
