<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'username'   => ['required', 'string', 'max:30', 'unique:users,username'],
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'cf-turnstile-response' => ['required'],
        ]);

        $response = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            [
                'secret' => config('services.turnstile.secret_key'),
                'response' => $request->input('cf-turnstile-response'),
                'remoteip' => $request->ip(),
            ]
        );

        if (! $response->json('success')) {
            return back()
                ->withErrors(['cf-turnstile-response' => 'Captcha verification failed.'])
                ->withInput();
        }

        $name = $request->first_name.' '.$request->last_name;

        $baseSlug = Str::slug($request->username);
        $slug = $baseSlug;
        $i = 1;

        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$i++;
        }

        $user = User::create([
            // 'name' => $request->name,
            'name'     => $name,
            'username' => $request->username,
            'slug'     => $slug,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'access_level' => '0',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    public function checkUsername(Request $request)
    {
        $exists = User::where('username', $request->username)->exists();

        return response()->json([
            'available' => ! $exists
        ]);
    }
}
