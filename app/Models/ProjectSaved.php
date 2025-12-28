<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectSaved extends Model
{
    use SoftDeletes;
    protected $table = 'project_saveds';

    protected $fillable = ['user_id', 'project_id'];
}
