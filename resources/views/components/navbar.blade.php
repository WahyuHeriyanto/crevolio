<nav class="w-full fixed top-0 z-50 bg-black/80 backdrop-blur border-b border-white/10">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LOGO --}}
        <img
            src="{{ asset('assets/logo/crevolio-white.png') }}"
            alt="Crevolio"
            class="h-12"
        >

        {{-- NAV --}}
        <div class="flex items-center gap-8 text-sm">
            <a href="#features"
            class="text-sm text-gray-300 hover:text-white transition">
                Features
            </a>


            <a
                href="{{ route('login') }}"
                class="text-gray-300 hover:text-white transition"
            >
                Login
            </a>
        </div>
    </div>
</nav>
