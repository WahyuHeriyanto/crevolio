<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectAccessRequest extends Model
{
    protected $fillable = [
        'project_id',
        'user_id', 
        'requester_id', 
        'status'
    ];

    public function project() { 
        return $this->belongsTo(Project::class); 
    }

    public function requester() { 
        return $this->belongsTo(User::class, 'requester_id'); 
    }
}