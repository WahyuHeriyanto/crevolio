<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
    <script defer src="//unpkg.com/alpinejs"></script>
</head>

<body class="min-h-screen grid md:grid-cols-2">

    {{-- LEFT --}}
    <div class="hidden md:flex flex-col justify-center px-16 bg-black text-white">
        <h1 class="text-4xl font-bold mb-6">
            Explore beautiful works
        </h1>

        <p class="text-gray-300 text-lg max-w-md">
            Crevolio helps you showcase your work and find people worth building with.
        </p>

        <div class="mt-10 text-sm text-gray-400">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-white underline">
                Register
            </a>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="flex items-center justify-center px-8">
        <div class="w-full max-w-md">

            <h2 class="text-3xl font-bold mb-2">Welcome back</h2>
            <p class="text-gray-500 mb-8">Log in to your account</p>


            

            {{-- EMAIL LOGIN --}}
            <form method="POST" action="{{ route('login') }}" x-data="{ show: false }">
                @csrf

                {{-- EMAIL --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email</label>

                    <div class="relative">
                        <!-- <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            ✉️
                        </span> -->

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full rounded-xl border-gray-300 focus:border-black focus:ring-black"
                        >
                    </div>

                    

                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- PASSWORD --}}
                <div class="mb-6">
    <label class="block text-sm font-medium mb-1">Password</label>

    <div class="relative" x-data="{ show: false }">
        <input
            :type="show ? 'text' : 'password'"
            name="password"
            required
            class="w-full pr-10 rounded-xl border-gray-300 focus:border-black focus:ring-black"
        >

        <button
            type="button"
            @click="show = !show"
            class="absolute inset-y-0 right-3 flex items-center text-gray-500"
        >
            <!-- Eye -->
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5
                         c4.478 0 8.268 2.943 9.542 7
                         -1.274 4.057 -5.064 7 -9.542 7
                         -4.477 0 -8.268 -2.943 -9.542 -7z"/>
            </svg>

            <!-- Eye Slash -->
            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M13.875 18.825A10.05 10.05 0 0112 19
                         c-4.478 0 -8.268 -2.943 -9.543 -7
                         a9.97 9.97 0 012.198 -3.568"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M6.223 6.223A9.969 9.969 0 0112 5
                         c4.478 0 8.268 2.943 9.543 7
                         a9.978 9.978 0 01-4.132 5.411"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 3l18 18"/>
            </svg>
        </button>
    </div>

    @error('password')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

                <button
                    type="submit"
                    class="w-full py-3 rounded-xl bg-black text-white hover:bg-gray-800 transition"
                >
                    Log in
                </button>
            </form>

            <div class="flex items-center gap-3 mb-6 mt-6">
                <div class="h-px bg-gray-200 flex-1"></div>
                <span class="text-xs text-gray-400">OR</span>
                <div class="h-px bg-gray-200 flex-1"></div>
            </div>

            {{-- GOOGLE LOGIN --}}
            <a
                href="{{ route('auth.google.redirect') }}"
                class="w-full flex items-center justify-center gap-3 border rounded-xl py-3 mb-6 hover:bg-gray-50 transition"
            >
                <img
                    src="https://www.svgrepo.com/show/475656/google-color.svg"
                    class="w-5 h-5"
                >
                <span class="text-sm font-medium">Continue with Google</span>
            </a>



            <p class="text-sm text-gray-500 mt-6 text-center">
                Forgot password?
                <a href="{{ route('password.request') }}" class="underline">
                    Reset here
                </a>
            </p>

        </div>
    </div>

</body>
</html>
