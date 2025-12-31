<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioMedia;
use App\Models\PortfolioTool;
use App\Models\ProgressStatus;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function create()
    {
        return view('portfolios.create', [
            'tools' => Tool::orderBy('name')->get(),
            'statuses' => ProgressStatus::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'project_field' => 'nullable|string|max:255',
            'access_link' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress_status_id' => 'nullable|exists:progress_statuses,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'tools' => 'nullable|array',
            'tools.*' => 'exists:tools,id',
        ]);

        DB::transaction(function () use ($request) {
            $portfolio = Portfolio::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'project_field' => $request->project_field,
                'access_link' => $request->access_link,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'progress_status_id' => $request->progress_status_id,
            ]);

            // Handle Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('portfolios', 'public');
                    PortfolioMedia::create([
                        'portfolio_id' => $portfolio->id,
                        'url' => $path,
                    ]);
                }
            }

            // Handle Tools
            if ($request->tools) {
                foreach ($request->tools as $toolId) {
                    PortfolioTool::create([
                        'portfolio_id' => $portfolio->id,
                        'tool_id' => $toolId,
                    ]);
                }
            }
        });

        return redirect()->route('profile.show', auth()->user()->username)
                         ->with('success', 'Portfolio created successfully.');
    }


    public function edit(Portfolio $portfolio)
    {
        // Pastikan hanya pemilik yang bisa mengedit
        if ($portfolio->user_id !== auth()->id()) {
            abort(403);
        }

        return view('portfolios.edit', [
            'portfolio' => $portfolio->load(['medias', 'tools.tool', 'progressStatus']),
            'tools' => Tool::orderBy('name')->get(),
            'statuses' => ProgressStatus::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'project_field' => 'nullable|string|max:255',
            'access_link' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress_status_id' => 'nullable|exists:progress_statuses,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'tools' => 'nullable|array',
            'tools.*' => 'exists:tools,id',
        ]);

        DB::transaction(function () use ($request, $portfolio) {
            $portfolio->update([
                'name' => $request->name,
                'description' => $request->description,
                'project_field' => $request->project_field,
                'access_link' => $request->access_link,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'progress_status_id' => $request->progress_status_id,
            ]);

            // Update Tools (Sync)
            PortfolioTool::where('portfolio_id', $portfolio->id)->delete();
            if ($request->tools) {
                foreach ($request->tools as $toolId) {
                    PortfolioTool::create([
                        'portfolio_id' => $portfolio->id,
                        'tool_id' => $toolId,
                    ]);
                }
            }

            // Handle Images (Hanya tambah jika ada upload baru)
            if ($request->hasFile('images')) {
                foreach ($portfolio->medias as $media) {
                    if (Storage::disk('public')->exists($media->url)) {
                        Storage::disk('public')->delete($media->url);
                    }
                    $media->delete();
                }

                foreach ($request->file('images') as $image) {
                    $path = $image->store('portfolios', 'public');
                    PortfolioMedia::create([
                        'portfolio_id' => $portfolio->id,
                        'url' => $path,
                    ]);
                }
            }
        });

        return redirect()->route('profile.show', auth()->user()->username)
                        ->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->user_id !== auth()->id()) {
            abort(403);
        }
        try {
            DB::transaction(function () use ($portfolio) {
                foreach ($portfolio->medias as $media) {
                    if (Storage::disk('public')->exists($media->url)) {
                        Storage::disk('public')->delete($media->url);
                    }
                }

                $portfolio->medias()->delete();
                $portfolio->tools()->delete();
                $portfolio->delete();
            });

            return back()->with('success', 'Portfolio deleted successfully.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }
}