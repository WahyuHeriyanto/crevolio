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

    confirm: '',
    confirmTouched: false,

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
    <input
        type="password"
        x-model="password"
        @focus="passwordTouched = true"
        name="password"
        placeholder="Password"
        required
        class="w-full rounded-xl border-gray-300"
    >

    <ul x-show="passwordTouched"
        class="text-sm mt-1 space-y-1">
        <li :class="validPassword ? 'text-green-600' : 'text-red-500'">
            • Minimum 8 characters, letters & numbers
        </li>
    </ul>
</div>

{{-- Confirm --}}
<div class="mb-6">
    <input
        type="password"
        x-model="confirm"
        @focus="confirmTouched = true"
        name="password_confirmation"
        placeholder="Confirm password"
        required
        class="w-full rounded-xl border-gray-300"
    >

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
