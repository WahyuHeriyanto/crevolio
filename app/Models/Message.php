<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message_type',
        'content',
        'is_deleted'
    ];

    protected $casts = [
        'content' => 'array',
        'is_deleted' => 'boolean'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function statuses()
    {
        return $this->hasMany(MessageStatus::class);
    }
}


