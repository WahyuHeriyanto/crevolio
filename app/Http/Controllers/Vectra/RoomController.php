<?php

namespace App\Http\Controllers\Vectra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Conversation;
use App\Models\ConversationParticipant;

class RoomController extends Controller
{
    public function index(Project $project)
    {
        $user = auth()->user();

        /**
         * 1. VALIDASI AKSES PROJECT
         */
        abort_unless(
            $project->owner_id === $user->id ||
            $project->detail->collaborators()
                ->where('access_user_id', $user->id)
                ->exists(),
            403
        );

        /**
         * 2. AMBIL / BUAT CONVERSATION GROUP
         */
        $conversation = Conversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => 'group',
            ],
            [
                'created_by' => $user->id,
            ]
        );

        /**
         * 3. SYNC PARTICIPANTS (COLLABORATORS)
         */
        $collaborators = $project->detail->collaborators;

        foreach ($collaborators as $collab) {
            ConversationParticipant::firstOrCreate([
                'conversation_id' => $conversation->id,
                'user_id' => $collab->access_user_id,
            ]);
        }

        /**
         * 4. LOAD DATA
         */
        $participants = $project->detail
            ->collaborators()
            ->with('user.profile')
            ->get();

        $messages = $conversation->messages()
            ->with('sender.profile')
            ->oldest() 
            ->take(50)
            ->get();

        return view('vectra.rooms', compact(
            'project',
            'conversation',
            'participants',
            'messages'
        ));
    }
}
