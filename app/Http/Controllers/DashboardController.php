<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $followingIds = $user->followings()->pluck('follow_user_id')->toArray();

        $projects = Project::with([
            'owner.profile', 
            'detail.field', 
            'detail.status', 
            'detail.tools.tool',
            'detail.collaborators.user.profile',
            'medias'
        ])
        ->withCount(['likes', 'saveds' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->withExists(['likes as is_liked' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->withExists(['saveds as is_saved' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderByRaw("FIELD(owner_id, " . (empty($followingIds) ? "0" : implode(',', $followingIds)) . ") DESC")
        ->orderBy('likes_count', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('dashboard.index', compact('projects'));
    }
}