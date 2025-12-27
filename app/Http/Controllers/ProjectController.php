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
}
