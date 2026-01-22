<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;


class MasterUserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search  = $request->get('search');

        $usersQuery = User::with([
            'profile.careerPosition'
        ]);

        if ($search) {
            $usersQuery->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $usersQuery
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        // === STATS ===
        $totalUsers = User::count();
        $completedProfiles = UserProfile::count();

        $genderStats = UserProfile::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total', 'gender');

        return view('admin.master-users.index', compact(
            'users',
            'totalUsers',
            'completedProfiles',
            'genderStats',
            'perPage'
        ));
    }
}

