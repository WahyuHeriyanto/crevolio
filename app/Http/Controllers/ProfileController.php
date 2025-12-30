<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\CareerPosition;
use App\Models\Expertise;
use App\Models\Tool;
use App\Models\UserTool;
use App\Models\UserExpertise;
use App\Models\UserProfile;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show(string $username)
    {
        $user = User::with([
            'profile.expertises.expertise',
            'profile.tools.tool',
            'profile.socialMedias.category',
        ])
        ->where('username', $username)
        ->firstOrFail();

        $currentUserId = auth()->id();
        $isOwner = auth()->check() && $currentUserId === $user->id;

        $projects = Project::with([
            'detail.field',
            'detail.status',
            'medias',
            'detail.tools.tool',
            'detail.collaborators.user.profile',
        ])
        ->where(function($query) use ($user) {
            $query->where('owner_id', $user->id) 
                ->orWhereHas('detail.collaborators', function($q) use ($user) {
                    $q->where('access_user_id', $user->id)
                        ->where('access_level', 0);
                });
        })
        ->when(!$isOwner, function($query) {
            $query->whereHas('detail.status', function($q) {
                $q->where('slug', '!=', 'private');
            });
        })
        ->latest()
        ->get();

        // AMBIL DATA PORTFOLIO
        $portfolios = Portfolio::with([
            'medias',
            'tools.tool',
            'progressStatus'
        ])
        ->where('user_id', $user->id)
        ->orderBy('start_date', 'desc')
        ->get();

        return view('profile.index', [
            'user' => $user,
            'profile' => $user->profile,
            'projects' => $projects,
            'portfolios' => $portfolios,
            'isOwner' => auth()->check() && auth()->id() === $user->id,
        ]);
    }


    public function edit()
    {
        $user = Auth::user()->load(['profile.expertises', 'profile.tools', 'profile.socialMedias']);
        
        return view('profile.edit', [
            'user' => $user,
            'careerPositions' => CareerPosition::all(),
            'expertises' => Expertise::all(),
            'tools' => Tool::all(),
            'userExpertiseIds' => $user->profile?->expertises->pluck('expertise_id')->toArray() ?? [],
            'userToolIds' => $user->profile?->tools->pluck('tool_id')->toArray() ?? [],
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $request->validate([
            'name' => 'required|string|max:255',
            'photo_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        DB::transaction(function () use ($request, $user, $profile) {
            // 1. Update User Dasar
            $user->update([
                'name' => $request->name,
            ]);

            // 2. Handle File Upload
            $photoPath = $profile->photo_profile;
            if ($request->hasFile('photo_profile')) {
                if ($photoPath) Storage::disk('public')->delete($photoPath);
                $photoPath = $request->file('photo_profile')->store('profile/photos', 'public');
            }

            $bgPath = $profile->background_image;
            if ($request->hasFile('background_image')) {
                if ($bgPath) Storage::disk('public')->delete($bgPath);
                $bgPath = $request->file('background_image')->store('profile/backgrounds', 'public');
            }

            // 3. Update User Profile
            $profile->update([
                'gender' => $request->gender,
                'birth' => $request->birth,
                'career_position_id' => $request->career_position_id,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'photo_profile' => $photoPath,
                'background_image' => $bgPath,
                'status' => $request->status ?? 'public',
            ]);

            // 4. Sync Expertises (Hapus yang lama, isi yang baru)
            UserExpertise::where('user_profile_id', $profile->id)->delete();
            $expertises = $request->input('expertises', []); // Ambil array atau default kosong
            foreach ($expertises as $expId) {
                UserExpertise::create([
                    'user_profile_id' => $profile->id,
                    'expertise_id' => $expId,
                ]);
            }

            // 5. Sync Tools
            UserTool::where('user_profile_id', $profile->id)->delete();
            $selectedTools = $request->input('tools', []);
            foreach ($selectedTools as $toolId) {
                UserTool::create([
                    'user_profile_id' => $profile->id,
                    'tool_id'         => $toolId,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }


    public function export(Request $request, $username)
    {
        $user = User::with([
            'profile.careerPosition',
            'profile.expertises.expertise',
            'profile.tools.tool',
        ])->where('username', $username)->firstOrFail();

        $theme = $request->query('theme', 'classic');

        $portfolios = Portfolio::where('user_id', $user->id)
            ->with(['medias', 'tools.tool', 'progressStatus'])
            ->orderBy('start_date', 'desc')
            ->get();

        return view("exports.portfolio-{$theme}", [
            'user' => $user,
            'portfolios' => $portfolios
        ]);
    }

}
