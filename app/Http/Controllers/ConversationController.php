<?php

namespace App\Http\Controllers;
use App\Models\Conversation;
use App\Models\ConversationParticipant;

use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function createPersonal(User $user)
    {
        $auth = auth()->id();

        $conversation = Conversation::where('type', 'personal')
            ->whereHas('participants', fn($q) => $q->where('user_id', $auth))
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create(['type' => 'personal']);

            ConversationParticipant::insert([
                ['conversation_id' => $conversation->id, 'user_id' => $auth, 'joined_at' => now()],
                ['conversation_id' => $conversation->id, 'user_id' => $user->id, 'joined_at' => now()],
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('sender')->latest()->paginate(30);
        return view('chat.show', compact('conversation', 'messages'));
    }
}

