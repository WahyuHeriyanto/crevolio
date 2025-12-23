<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CareerPosition extends Model
{
    use SoftDeletes;

    protected $fillable = ['slug', 'name'];
}
