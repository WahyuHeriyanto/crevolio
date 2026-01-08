<?php

namespace App\Http\Controllers\Vectra;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();

    $projects = Project::with([
            'detail.field',
            'detail.status',
            'medias',
            'detail.collaborators.user.profile',
        ])
        ->where(function ($query) use ($user) {
            $query->where('owner_id', $user->id)
                  ->orWhereHas('detail.collaborators', function ($q) use ($user) {
                      $q->where('access_user_id', $user->id)
                        ->whereIn('access_level', [0, 1]);
                  });
        })
        ->latest()
        ->paginate(9); // Ganti get() jadi paginate biar bisa di-scroll per 9 data

    // CEK: Kalo request-nya dari AJAX/Fetch (pas scroll)
    if ($request->ajax()) {
        return response()->json([
            // Kita render view khusus yang isinya cuma loop kartu doang
            'html' => view('vectra.partials._project_list', compact('projects'))->render(),
            'nextPage' => $projects->nextPageUrl()
        ]);
    }

    return view('vectra.dashboard', compact('projects'));
}
}