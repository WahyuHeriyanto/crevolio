<?php

namespace App\Http\Controllers;

use App\Models\{
    Project,
    ProjectDetail,
    ProjectMedia,
    ProjectTool,
    Tool,
    ProjectField,
    ProjectStatus,
    ProgressStatus,
    ProjectAccess,
    ProjectLike,
    ProjectSaved,
    ProjectAccessRequest,
    UserNotification,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create', [
            'fields' => ProjectField::orderBy('name')->get(),
            'statuses' => ProjectStatus::orderBy('name')->get(),
            'tools' => Tool::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'project_field_id' => 'nullable|exists:project_fields,id',
            'project_status_id' => 'nullable|exists:project_statuses,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'images.*' => 'image|max:2048',
            'tools' => 'nullable|array',
            'tools.*' => 'exists:tools,id',
        ]);

        DB::transaction(function () use ($request) {

            $progressStarted = ProgressStatus::where('slug', 'started')->first();

            $detail = ProjectDetail::create([
                'description' => $request->description,
                'project_field_id' => $request->project_field_id,
                'project_status_id' => $request->project_status_id,
                'progress_status_id' => $progressStarted?->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'member_count' => 0,
                'like_count' => 0,
            ]);

            $project = Project::create([
                'name' => $request->name,
                'owner_id' => auth()->id(),
                'project_detail_id' => $detail->id,
            ]);

            $access = ProjectAccess::create([
                'access_user_id' => auth()->id(),
                'access_level' => 1,
                'project_role' => 'Owner',
                'project_detail_id' => $detail->id,
            ]);

            // dd(
            //     $request->all(),
            //     $request->hasFile('images'),
            //     $request->file('images')
            // );
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('projects', 'public');

                    ProjectMedia::create([
                        'project_id' => $project->id,
                        'url' => $path,
                    ]);
                }
            }

            if ($request->tools) {
                foreach ($request->tools as $toolId) {
                    ProjectTool::create([
                        'project_detail_id' => $detail->id,
                        'tool_id' => $toolId,
                    ]);
                }
            }


        });

        return redirect()
            ->route('profile.show', auth()->user()->username)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load([
            'detail.field',
            'detail.status',
            'detail.progress',
            'detail.tools.tool',
            'detail.collaborators.user.profile',
            'medias',
            'owner.profile',
        ]);

        $user = auth()->user();
    $isOwner = auth()->check() && $user->id === $project->owner_id;

    $pendingRequestsCount = 0;
    if ($isOwner) {
        $pendingRequestsCount = ProjectAccessRequest::where('project_id', $project->id)
            ->where('status', 'pending')
            ->count();
    }
    
    $isCollaborator = false;
    if (auth()->check()) {
        $isCollaborator = $project->detail->collaborators
            ->where('access_user_id', $user->id)
            ->isNotEmpty();
    }

    return view('projects.show', compact('project', 'isOwner', 'isCollaborator', 'pendingRequestsCount'));
    }

    public function edit(Project $project)
    {
        if (auth()->id() !== $project->owner_id) {
            abort(403, 'Unauthorized action.');
        }

        $fields = ProjectField::all();
        $statuses = ProjectStatus::all();
        $tools = Tool::all();

        return view('projects.edit', compact('project', 'fields', 'statuses', 'tools'));
    }

    public function update(Request $request, Project $project)
    {
        if (auth()->id() !== $project->owner_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'project_field_id' => 'required|exists:project_fields,id',
            'project_status_id' => 'required|exists:project_statuses,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'tools' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $project->update([
            'name' => $request->name,
        ]);

        $project->detail->update([
            'description' => $request->description,
            'project_field_id' => $request->project_field_id,
            'project_status_id' => $request->project_status_id,
            'start_date' => $request->start_date,
            'end_date' => $request->has('ongoing') ? null : $request->end_date,
        ]);

        $project->detail->tools()->delete();
        if ($request->has('tools')) {
            foreach ($request->tools as $toolId) {
                $project->detail->tools()->create(['tool_id' => $toolId]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('projects', 'public');
                $project->medias()->create(['url' => $path]);
            }
        }

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        if (auth()->id() !== $project->owner_id) abort(403);
        
        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Project deleted successfully');
    }

    public function toggleLike(Project $project)
    {
        $user = auth()->user();
        $like = ProjectLike::where('user_id', $user->id)->where('project_id', $project->id)->first();

        if ($like) {
            $like->delete();
            $project->detail->decrement('like_count');
            $status = false;
        } else {
            ProjectLike::create(['user_id' => $user->id, 'project_id' => $project->id]);
            $project->detail->increment('like_count');
            $status = true;
        }

        return response()->json([
            'status' => $status,
            'like_count' => $project->detail->like_count
        ]);
    }

    public function toggleSave(Project $project)
    {
        $user = auth()->user();
        $save = ProjectSaved::where('user_id', $user->id)->where('project_id', $project->id)->first();

        if ($save) {
            $save->delete();
            $status = false;
        } else {
            ProjectSaved::create(['user_id' => $user->id, 'project_id' => $project->id]);
            $status = true;
        }

        return response()->json(['status' => $status]);
    }

    public function export(Project $project)
    {
        return view('projects.export', compact('project'));
    }


    public function join(Project $project)
    {
        $user = auth()->user();

        // 1. Cek apakah sudah pernah request
        $existingRequest = ProjectAccessRequest::where('project_id', $project->id)
            ->where('requester_id', $user->id)
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You have already sent a join request.');
        }

        // 2. Simpan Request
        ProjectAccessRequest::create([
            'project_id' => $project->id,
            'user_id' => $project->owner_id,
            'requester_id' => $user->id,
            'status' => 'pending'
        ]);

        // 3. Simpan Notifikasi untuk Owner
        UserNotification::create([
            'user_id' => $project->owner_id,
            'type' => 'project_join',
            'title' => 'New Join Request from ' . $user->name,
            'message' => "Wants to join your project: {$project->name}. Review the request to approve or reject.",
            'target_url' => route('projects.requests'), // Halaman list request
            'is_read' => 0
        ]);

        return back()->with('success', 'Join request sent successfully!');
    }

    // Menampilkan daftar request (untuk owner project)
    public function requests()
    {
        $requests = ProjectAccessRequest::with(['requester', 'project'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('projects.requests', compact('requests'));
    }

    // Menampilkan daftar notifikasi
    public function notifications()
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->latest()
            ->get();
        
        // Mark as read saat dibuka
        UserNotification::where('user_id', auth()->id())->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    // Action Approve/Reject
    public function handleRequest(ProjectAccessRequest $request, $action)
    {
        if (auth()->id() !== $request->user_id) abort(403);

        if ($action === 'approve') {
            $request->update(['status' => 'approved']);
            
            // Tambahkan ke table project_access (collaborators)
            \App\Models\ProjectAccess::create([
                'access_user_id' => $request->requester_id,
                'access_level' => 0, 
                'project_role' => 'Contributor',
                'project_detail_id' => $request->project->project_detail_id,
            ]);

            // Kirim notif balik ke requester
            UserNotification::create([
                'user_id' => $request->requester_id,
                'type' => 'request_approved',
                'title' => 'Join Request Approved!',
                'message' => "Congratulations! You are now a contributor in {$request->project->name}.",
                'target_url' => route('projects.show', $request->project->id),
                'is_read' => 0
            ]);

        } else {
            $request->update(['status' => 'rejected']);
        }

        return back()->with('success', 'Request processed.');
    }

}
