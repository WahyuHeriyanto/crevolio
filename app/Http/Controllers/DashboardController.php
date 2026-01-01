<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $followingIds = $user->followings()->pluck('follow_user_id')->toArray();

        // Ambil data awal untuk Project
        $projects = Project::with(['owner.profile', 'detail.field', 'detail.status', 'detail.tools.tool', 'detail.collaborators.user.profile', 'medias'])
            ->where(function($query) use ($user) {
                $query->whereHas('detail.status', function($q) { $q->where('slug', '!=', 'private'); })
                    ->orWhere('owner_id', $user->id);
            })
            ->withCount(['likes', 'saveds' => function($query) use ($user) { $query->where('user_id', $user->id); }])
            ->withExists(['likes as is_liked', 'saveds as is_saved' => function($query) use ($user) { $query->where('user_id', $user->id); }])
            ->orderByRaw("FIELD(owner_id, " . (empty($followingIds) ? "0" : implode(',', $followingIds)) . ") DESC")
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Ambil data awal untuk Crevolians
        $crevolians = User::with(['profile.expertises.expertise','profile.careerPosition'])
            ->withCount(['projectAccesses as collaboration_count' => function($query) {
                $query->whereIn('access_level', [0, 1]);
            }])
            ->where('id', '!=', $user->id)
            ->latest()
            ->paginate(9);

        if ($request->ajax()) {
            if ($request->type === 'projects') {
                return response()->json([
                    'html' => view('dashboard.partials.load-projects', compact('projects'))->render(),
                    'nextPage' => $projects->nextPageUrl()
                ]);
            }

            if ($request->type === 'crevolians') {
                return response()->json([
                    'html' => view('dashboard.partials.load-crevolians', compact('crevolians'))->render(),
                    'nextPage' => $crevolians->nextPageUrl()
                ]);
            }
        }

        return view('dashboard.index', compact('projects', 'crevolians'));
    }
}