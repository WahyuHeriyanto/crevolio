<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {

            // ===== Generate username =====
            $baseUsername = Str::slug($googleUser->getName());
            $username = $baseUsername;
            $i = 1;

            while (User::where('username', $username)->exists()) {
                $username = "{$baseUsername}{$i}";
                $i++;
            }

            // ===== Generate slug =====
            $baseSlug = $username;
            $slug = $baseSlug;
            $i = 1;

            while (User::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$i}";
                $i++;
            }

            $user = User::create([
                'name' => $googleUser->getName(),
                'username' => $username,
                'slug' => $slug,
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'access_level' => 0, 
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
