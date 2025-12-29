<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioMedia extends Model
{
    use SoftDeletes;
    protected $table = 'portfolio_medias';
    protected $fillable = ['portfolio_id', 'url'];
}
