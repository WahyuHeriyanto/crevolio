<?php

namespace App\Http\Controllers\Vectra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Conversation;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        /**
         * STEP 1
         * Ambil project pertama yang user ikut (via ProjectDetail → collaborators)
         */
        $project = Project::whereHas('detail.collaborators', function ($q) use ($userId) {
            $q->where('access_user_id', $userId);
        })
        ->with([
            'detail.collaborators.user.profile',
        ])
        ->firstOrFail();

        /**
         * STEP 2
         * Ambil / buat conversation group per project
         */
        $conversation = Conversation::firstOrCreate([
            'project_id' => $project->id,
            'type'       => 'group',
        ]);

        /**
         * STEP 3
         * Ambil messages
         */
        $messages = $conversation->messages()
            ->with('sender.profile')
            ->latest()
            ->take(50)
            ->get()
            ->reverse(); // biar urut bawah kayak chat normal

        return view('vectra.rooms', [
            'project'      => $project,
            'participants' => $project->detail->collaborators,
            'conversation' => $conversation,
            'messages'     => $messages,
        ]);
    }
}
