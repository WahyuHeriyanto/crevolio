<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
