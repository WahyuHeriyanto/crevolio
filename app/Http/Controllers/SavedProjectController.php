<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil project yang di-save oleh user yang sedang login
        $savedProjects = Project::whereHas('saveds', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with([
            'detail.field',
            'detail.status',
            'detail.tools.tool',
            'detail.collaborators.user.profile',
            'medias',
            'owner.profile'
        ])
        ->latest()
        ->paginate(10);

        return view('projects.saved', [
            'projects' => $savedProjects,
            'user' => $user
        ]);
    }
}