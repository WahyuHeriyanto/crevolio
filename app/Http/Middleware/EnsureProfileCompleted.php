<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $hasProfile = UserProfile::where('user_id', Auth::id())->exists();

        if (! $hasProfile && ! $request->routeIs('onboarding.*')) {
            return redirect()->route('onboarding.index');
        }

        if ($hasProfile && $request->routeIs('onboarding.*')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}

