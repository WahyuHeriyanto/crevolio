<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSocialMedia extends Model
{
    use SoftDeletes;

    protected $table = 'user_social_medias';

    protected $fillable = [
        'user_profile_id',
        'category_social_media_id',
        'link',
    ];

    public function category()
    {
        return $this->belongsTo(CategorySocialMedia::class, 'category_social_media_id');
    }
}
