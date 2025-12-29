<div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 text-center transition hover:shadow-md group">
    
    {{-- Profile Photo --}}
    <div class="relative w-20 h-20 mx-auto mb-4">
        <img
            src="{{ asset('storage/' . ($user->profile->photo_profile ?? 'default.jpg')) }}"
            class="w-20 h-20 rounded-full object-cover border-2 border-white shadow-sm group-hover:scale-105 transition duration-300"
            alt="{{ $user->name }}"
        >
        <div class="absolute bottom-0 right-0 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full"></div>
    </div>

    {{-- Info --}}
    <div class="font-bold text-gray-900 group-hover:text-indigo-600 transition">
        {{ $user->name }}
    </div>

    <div class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-3">
        {{ $user->profile?->careerPosition?->name ?? 'Creative Member' }}
    </div>

    {{-- Expertises (Badges) --}}
    <div class="flex flex-wrap justify-center gap-1 mb-5">
        @if($user->profile && $user->profile->expertises)
            @foreach($user->profile->expertises->take(2) as $exp)
                <span class="px-2 py-0.5 bg-gray-50 text-[10px] text-gray-500 rounded-md border border-gray-100">
                    {{ $exp->expertise->name ?? 'N/A' }}
                </span>
            @endforeach
            
            @if($user->profile->expertises->count() > 2)
                <span class="text-[10px] text-gray-400 self-center">
                    +{{ $user->profile->expertises->count() - 2 }}
                </span>
            @endif
        @else
            <span class="text-[10px] text-gray-400 italic">No expertise listed</span>
        @endif
    </div>

    {{-- Stats Mini --}}
    <div class="flex items-center justify-center gap-4 py-3 border-t border-gray-50 mb-4">
        <div class="text-center">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Followers</p>
            <p class="text-sm font-bold">{{ number_format($user->profile->followers ?? 0) }}</p>
        </div>
        <div class="w-[1px] h-4 bg-gray-100"></div>
        <div class="text-center">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Following</p>
            <p class="text-sm font-bold">{{ number_format($user->profile->following ?? 0) }}</p>
        </div>
    </div>

    <a 
        href="{{ route('profile.show', $user->username) }}" 
        class="block w-full py-3 rounded-2xl bg-gray-50 text-gray-900 text-xs font-bold hover:bg-black hover:text-white transition duration-300 border border-gray-100"
    >
        View Profile
    </a>
</div>