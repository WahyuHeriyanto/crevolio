<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserProfile;
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

        return view('profile.index', [
            'user' => $user,
            'profile' => $user->profile,
            'isOwner' => auth()->check() && auth()->id() === $user->id,
        ]);
    }
}
