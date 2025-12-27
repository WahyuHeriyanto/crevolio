<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectAccess extends Model
{
    use SoftDeletes;
    protected $table = 'project_acceses';

    protected $fillable = [
        'access_user_id',
        'access_level',
        'project_role',
        'project_detail_id',
    ];
}
