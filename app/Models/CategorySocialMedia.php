<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategorySocialMedia extends Model
{
    use SoftDeletes;

    protected $table = 'category_social_medias';

    protected $fillable = [
        'slug',
        'name',
        'icon',
    ];
}
