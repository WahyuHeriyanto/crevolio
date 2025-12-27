<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description',
        'project_field_id',
        'start_date',
        'end_date',
        'project_status_id',
        'progress_status_id',
        'member_count',
        'like_count',
    ];

    public function tools()
    {
        return $this->hasMany(ProjectTool::class);
    }
}
