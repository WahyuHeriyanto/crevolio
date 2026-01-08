@php
    $user = auth()->user();
    $photo = $user->profile?->photo_profile
        ? asset('storage/' . $user->profile->photo_profile)
        : asset('assets/images/photo-profile-default.png');
@endphp

<header class="h-16 bg-white border-b flex items-center justify-between px-6">
    <div class="font-bold text-lg tracking-tight">
        Crevolio <span class="text-indigo-600">Vectra</span>
    </div>

    <div class="flex items-center gap-6">
        {{-- NOTIFICATION --}}
        <button class="relative text-gray-500 hover:text-indigo-600 transition">
            <i class="fa-solid fa-bell text-xl"></i>
        </button>

        {{-- USER --}}
        <div class="flex items-center gap-3">
            <img
                src="{{ $photo }}"
                class="w-9 h-9 rounded-full object-cover border"
            />
            <span class="text-sm font-medium">
                {{ $user->name }}
            </span>
        </div>
    </div>
</header>
