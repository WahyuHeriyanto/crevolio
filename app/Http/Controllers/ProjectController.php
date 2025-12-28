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
                'project_role' => 'Admin',
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
    
    $isCollaborator = false;
    if (auth()->check()) {
        $isCollaborator = $project->detail->collaborators
            ->where('access_user_id', $user->id)
            ->isNotEmpty();
    }

    return view('projects.show', compact('project', 'isOwner', 'isCollaborator'));
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

}
