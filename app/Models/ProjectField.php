<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectField extends Model
{
    use SoftDeletes;

    protected $fillable = ['slug', 'name'];
}
