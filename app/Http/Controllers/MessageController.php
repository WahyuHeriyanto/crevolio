<?php

namespace App\Http\Controllers;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageStatus;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Conversation $conversation)
    {
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message_type' => 'text',
            'content' => ['text' => $request->message],
        ]);

        foreach ($conversation->participants as $participant) {
            MessageStatus::create([
                'message_id' => $message->id,
                'user_id' => $participant->user_id,
                'status' => $participant->user_id == auth()->id() ? 'read' : 'sent',
            ]);
        }

        return back();
    }
}

