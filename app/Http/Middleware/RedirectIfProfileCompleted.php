<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserProfile;

class RedirectIfProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $hasProfile = UserProfile::where('user_id', auth()->id())->exists();

            if ($hasProfile) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
