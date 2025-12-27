<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectMedia extends Model
{
    use SoftDeletes;
    protected $table = 'project_medias';

    protected $fillable = [
        'project_id',
        'url',
    ];
}
