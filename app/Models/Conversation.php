<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'project_id',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
