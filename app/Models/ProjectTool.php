<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTool extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_detail_id',
        'tool_id',
        'custom_tool',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
