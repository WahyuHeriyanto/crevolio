<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id', 'name', 'description', 'project_field', 'access_link', 'start_date', 'end_date', 'progress_status_id'];

    public function medias() { return $this->hasMany(PortfolioMedia::class); }
    public function tools() { return $this->hasMany(PortfolioTool::class); }
    public function progressStatus() { return $this->belongsTo(ProgressStatus::class); }

}
