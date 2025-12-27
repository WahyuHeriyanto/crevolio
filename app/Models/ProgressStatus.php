<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgressStatus extends Model
{
    use SoftDeletes;
    protected $table = 'progress_statuses';

    protected $fillable = ['slug', 'name'];
}
