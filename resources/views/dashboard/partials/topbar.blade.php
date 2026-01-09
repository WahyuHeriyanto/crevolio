<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        @php
            $unreadCount = 0;
            $userPhoto = asset('assets/images/photo-profile-default.png');

            if(auth()->check()) {
                $unreadCount = \App\Models\UserNotification::where('user_id', auth()->id())->where('is_read', 0)->count();
                $userPhoto = auth()->user()->profile?->photo_profile 
                            ? asset('storage/' . auth()->user()->profile->photo_profile) 
                            : asset('assets/images/photo-profile-default.png');
            }
        @endphp
        
        {{-- LOGO --}}
        <div class="flex items-center gap-2 font-bold text-lg">
            <a href="/">Crevolio</a>
        </div>

        <div class="flex items-center gap-4">
            {{-- LINK HANYA UNTUK YANG SUDAH LOGIN --}}
            @auth
                <a href="{{ route('vectra.dashboard') }}" 
                class="text-sm font-bold text-purple-600 hover:text-purple-800 transition-colors">
                    Go Vectra
                </a>
                <a href="{{ route('profile.show', auth()->user()->username) }}" class="text-sm font-semibold text-gray-600 hover:text-black transition-colors">
                    My Projects
                </a>
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-black transition-colors">
                    Dashboard
                </a>
                {{-- ICON NOTIFIKASI --}}
                <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-500 hover:text-indigo-600 transition-colors">
                    <i class="fa-solid fa-bell text-xl"></i>
                    @if($unreadCount > 0)
                        <span class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>
            @endauth

            {{-- Separator --}}
            <div class="h-8 w-[1px] bg-gray-200"></div>

            {{-- USER MENU --}}
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center gap-3"
                >
                    <img
                        src="{{ $userPhoto }}"
                        class="w-9 h-9 rounded-full object-cover border-2 border-transparent"
                        alt="Profile Photo"
                    />
                    <span class="text-sm font-medium">
                        {{ auth()->user()->name ?? 'Guest' }}
                    </span>
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 mt-3 w-40 bg-white border rounded-xl shadow-lg overflow-hidden z-50"
                >
                    @auth
                        {{-- MENU JIKA SUDAH LOGIN --}}
                        <a
                            href="{{ route('profile.show', auth()->user()->username) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-50"
                        >
                            Profile
                        </a>
                        <a
                            href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-50"
                        >
                            Edit Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm hover:bg-gray-50 text-red-600">
                                Logout
                            </button>
                        </form>
                    @else
                        {{-- MENU JIKA GUEST (HANYA LOGIN) --}}
                        <a
                            href="{{ route('login') }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-50"
                        >
                            Login
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-50"
                        >
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>