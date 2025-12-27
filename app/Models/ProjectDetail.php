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

        public function field()
    {
        return $this->belongsTo(ProjectField::class, 'project_field_id');
    }

    public function status()
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }

    public function collaborators()
    {
        return $this->hasMany(ProjectAccess::class, 'project_detail_id');
    }

}
