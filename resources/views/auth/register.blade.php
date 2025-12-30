<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register — Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
    <script defer src="//unpkg.com/alpinejs"></script>
</head>

<body class="min-h-screen grid md:grid-cols-2">

{{-- LEFT --}}
<div class="hidden md:flex flex-col justify-center px-16 bg-black text-white">
    <h1 class="text-4xl font-bold mb-6">Let's build together</h1>
    <p class="text-gray-300 text-lg max-w-md">
        Join us, create together, share, and collaborate to create a beautiful world.
    </p>
</div>

{{-- RIGHT --}}
<div class="flex items-center justify-center px-8">
<div class="w-full max-w-md">

<h2 class="text-3xl font-bold mb-2">Create Account</h2>
<p class="text-gray-500 mb-8">Create your Crevolio account and start collaborating.</p>

<form method="POST" action="{{ route('register') }}"
x-data="{
    username: '',
    available: null,
    usernameTouched: false,

    password: '',
    passwordTouched: false,
    showPassword: false,

    confirm: '',
    confirmTouched: false,
    showConfirm: false,

    checkUsername() {
        if (this.username.length < 3) return;
        fetch('/check-username?username=' + this.username)
            .then(r => r.json())
            .then(d => this.available = d.available);
    },

    get validPassword() {
        return this.password.length >= 8 &&
               /[A-Za-z]/.test(this.password) &&
               /[0-9]/.test(this.password);
    },

    get canSubmit() {
        return this.available === true &&
               this.validPassword &&
               this.password === this.confirm;
    }
}">
@csrf

<div class="grid grid-cols-2 gap-3 mb-4">
    <input name="first_name" placeholder="First name" required class="rounded-xl border-gray-300">
    <input name="last_name" placeholder="Last name" required class="rounded-xl border-gray-300">
</div>

{{-- Username --}}
<div class="mb-4">
    <input x-model="username" @input.debounce.500ms="checkUsername"
           name="username" placeholder="Username" required
           class="w-full rounded-xl border-gray-300">

    <p x-show="available === false" class="text-sm text-red-500 mt-1">
        Username already taken
    </p>
    <p x-show="available === true" class="text-sm text-green-600 mt-1">
        Username available
    </p>
</div>

{{-- Email --}}
<div class="mb-4">
    <input type="email" name="email" placeholder="Email" required
           class="w-full rounded-xl border-gray-300">
</div>

{{-- Password --}}
<div class="mb-2">
    <div class="relative">
        <input
            :type="showPassword ? 'text' : 'password'"
            x-model="password"
            @focus="passwordTouched = true"
            name="password"
            placeholder="Password"
            required
            class="w-full pr-10 rounded-xl border-gray-300 focus:border-black focus:ring-black"
        >
        <button
            type="button"
            @click="showPassword = !showPassword"
            class="absolute inset-y-0 right-3 flex items-center text-gray-500"
        >
            {{-- Eye Icon --}}
            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            {{-- Eye Slash Icon --}}
            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.198-3.568m4.968-1.29A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.978 9.978 0 01-4.132 5.411m0 0L3 3l18 18"/>
            </svg>
        </button>
    </div>

    <ul x-show="passwordTouched"
        class="text-sm mt-1 space-y-1">
        <li :class="validPassword ? 'text-green-600' : 'text-red-500'">
            • Minimum 8 characters, letters & numbers
        </li>
    </ul>
</div>

{{-- Confirm --}}
<div class="mb-6">
    <div class="relative">
        <input
            :type="showConfirm ? 'text' : 'password'"
            x-model="confirm"
            @focus="confirmTouched = true"
            name="password_confirmation"
            placeholder="Confirm password"
            required
            class="w-full pr-10 rounded-xl border-gray-300 focus:border-black focus:ring-black"
        >
        <button
            type="button"
            @click="showConfirm = !showConfirm"
            class="absolute inset-y-0 right-3 flex items-center text-gray-500"
        >
            <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.198-3.568m4.968-1.29A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.978 9.978 0 01-4.132 5.411m0 0L3 3l18 18"/>
            </svg>
        </button>
    </div>

    <p x-show="confirmTouched && password !== confirm"
        class="text-sm text-red-500 mt-1">
            Passwords do not match
    </p>
</div>

<button
    :disabled="!canSubmit"
    :class="canSubmit ? 'bg-black hover:bg-gray-800' : 'bg-gray-300 cursor-not-allowed'"
    class="w-full py-3 rounded-xl text-white transition">
    Create account
</button>

</form>

</div>
</div>

</body>
</html>
