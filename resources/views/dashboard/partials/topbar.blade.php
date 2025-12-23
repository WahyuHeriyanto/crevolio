<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">

        {{-- LOGO --}}
        <div class="flex items-center gap-2 font-bold text-lg">
            <span>Crevolio</span>
        </div>

        {{-- USER MENU --}}
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="flex items-center gap-3"
            >
                <img
                    src="https://i.pravatar.cc/40"
                    class="w-8 h-8 rounded-full"
                />
                <span class="text-sm font-medium">
                    {{ Auth::user()->name ?? 'User' }}
                </span>
            </button>

            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 mt-3 w-40 bg-white border rounded-xl shadow-lg overflow-hidden"
            >
                <a
                    href="{{ route('profile.show', Auth::user()->username) }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-50"
                >
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
