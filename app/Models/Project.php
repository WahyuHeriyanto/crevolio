<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'name',
        'owner_id',
        'project_detail_id',
    ];

    protected static function booted()
    {
        static::creating(function ($project) {
            $project->slug = Str::slug($project->name) . '-' . Str::random(6);
        });
    }

    public function detail()
    {
        return $this->belongsTo(ProjectDetail::class, 'project_detail_id');
    }

    public function medias()
    {
        return $this->hasMany(ProjectMedia::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function likes() 
    { 
        return $this->hasMany(ProjectLike::class); 
    }

    public function saveds() 
    { 
        return $this->hasMany(ProjectSaved::class); 
    }

    public function accessRequests()
    {
        return $this->hasMany(ProjectAccessRequest::class, 'project_id');
    }
}
