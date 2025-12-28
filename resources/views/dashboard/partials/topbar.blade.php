<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        @php
            $unreadCount = \App\Models\UserNotification::where('user_id', auth()->id())->where('is_read', 0)->count();
            $userPhoto = auth()->user()->profile->photo_profile 
                        ? asset('storage/' . auth()->user()->profile->photo_profile) 
                        : asset('images/default-avatar.png');
        @endphp
        
        {{-- LOGO --}}
        <div class="flex items-center gap-2 font-bold text-lg">
            <span>Crevolio</span>
        </div>

        <div class="flex items-center gap-4">
            {{-- ICON NOTIFIKASI --}}
            <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-500 hover:text-indigo-600 transition-colors">
                <i class="fa-solid fa-bell text-xl"></i>
                @if($unreadCount > 0)
                    <span class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a>

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
                        {{ Auth::user()->name ?? 'User' }}
                    </span>
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 mt-3 w-40 bg-white border rounded-xl shadow-lg overflow-hidden z-50"
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
</div>