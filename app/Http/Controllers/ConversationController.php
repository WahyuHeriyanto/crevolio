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
        $messages = $conversation->messages()->with('sender.profile')->oldest()->get();
        $participants = $conversation->participants()->with('user.profile')->get();
        $project = $conversation->project;

        return view('vectra.rooms', compact('conversation', 'messages', 'participants', 'project'));
    }

    public function clearMessages(Conversation $conversation)
    {
        if ($conversation->project && $conversation->project->owner_id !== auth()->id()) {
            return back()->with('error', 'Only the project owner can clear the chat room.');
        }

        $conversation->messages()->delete();
        return back()->with('success', 'Chat cleared');
    }
}

