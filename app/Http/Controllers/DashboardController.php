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
        
        // Ambil tab aktif dari request, default ke 'projects'
        $activeTab = $request->get('tab', 'projects');

        // --- LOGIKA PROJECTS ---
        $projectsQuery = Project::with(['owner.profile', 'detail.field', 'detail.status', 'detail.tools.tool', 'detail.collaborators.user.profile', 'medias'])
            ->where(function($query) use ($user) {
                $query->whereHas('detail.status', function($q) { $q->where('slug', '!=', 'private'); })
                    ->orWhere('owner_id', $user->id);
            });

        // Filter Project hanya jalan JIKA tab-nya projects
        if ($activeTab === 'projects') {
            $projectsQuery->when($request->search, function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            })
            ->when($request->field, function($q) use ($request) {
                $q->whereHas('detail.field', function($f) use ($request) {
                    $f->where('id', $request->field);
                });
            })
            ->when($request->only_open === 'true', function($q) {
                $q->whereHas('detail.status', function($s) {
                    $s->where('slug', 'open');
                });
            });
        }

        $projects = $projectsQuery->withCount(['likes', 'saveds' => function($query) use ($user) { $query->where('user_id', $user->id); }])
            ->withExists(['likes as is_liked', 'saveds as is_saved' => function($query) use ($user) { $query->where('user_id', $user->id); }])
            ->orderByRaw("FIELD(owner_id, " . (empty($followingIds) ? "0" : implode(',', $followingIds)) . ") DESC")
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(5)->withQueryString();

        // --- LOGIKA CREVOLIANS ---
        $crevoliansQuery = User::with(['profile.expertises.expertise','profile.careerPosition'])
            ->where('id', '!=', $user->id);

        // Filter Crevolians hanya jalan JIKA tab-nya collaborators
        if ($activeTab === 'collaborators') {
            $crevoliansQuery->when($request->search, function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            })
            ->when($request->expertise, function($q) use ($request) {
                $q->whereHas('profile.expertises.expertise', function($e) use ($request) {
                    $e->where('id', $request->expertise);
                });
            });
        }

        $crevolians = $crevoliansQuery->withCount(['projectAccesses as collaboration_count' => function($query) {
                $query->whereIn('access_level', [0, 1]);
            }])
            ->latest()
            ->paginate(9)->withQueryString();

        $fields = \App\Models\ProjectField::all();
        $expertises = \App\Models\Expertise::all();

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

        return view('dashboard.index', compact('projects', 'crevolians', 'fields', 'expertises', 'activeTab'));
    }
}