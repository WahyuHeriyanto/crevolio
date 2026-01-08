<div class="bg-white rounded-2xl p-5 shadow-sm sticky top-24">
    @php
            $userPhoto = auth()->user()->profile->photo_profile 
                        ? asset('storage/' . auth()->user()->profile->photo_profile) 
                        : asset('assets/images/photo-profile-default.png');
            $pendingRequestsCount = \App\Models\FollowRequest::where('user_id', auth()->id())->count();
    @endphp
    <div class="flex items-center gap-4 mb-4">
        <div class="relative">
            <img
                src="{{ $userPhoto }}"
                class="w-14 h-14 rounded-full object-cover"
            />

            @if(auth()->user()->profile?->isOnline())
                <span
                    class="absolute bottom-0 right-0 w-4 h-4
                        bg-emerald-500 border-2 border-white rounded-full
                        ring-2 ring-emerald-300 animate-pulse"
                    title="Online"
                ></span>
            @endif
        </div>
        <div>
            <div class="font-semibold">
                {{ Auth::user()->name ?? 'Your Name' }}
            </div>
            <div class="text-sm text-gray-500">
                {{Auth::user()->profile?->careerPosition?->name ?? ''}}
            </div>
        </div>
    </div>

    <div class="text-sm text-gray-600 mb-4 line-clamp-3">
        {{ Auth::user()->profile?->description ?? ''}}
    </div>

    <hr class="border-gray-50 mb-4">

    <div class="space-y-3 text-sm">
        <a href="{{ route('projects.saved') }}" class="flex items-center justify-between group text-gray-700 hover:text-black transition">
            <span class="flex items-center gap-2">
                <i class="fa-regular fa-bookmark text-gray-400 group-hover:text-black"></i>
                Saved Projects
            </span>
        </a>

        {{-- LINK KE FOLLOW REQUESTS --}}
        <a href="{{ route('profile.follow-requests') }}" class="flex items-center justify-between group text-gray-700 hover:text-black transition">
            <span class="flex items-center gap-2">
                <i class="fa-regular fa-user text-gray-400 group-hover:text-black"></i>
                Follow Requests
            </span>
            
            @if($pendingRequestsCount > 0)
                <span class="bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm shadow-indigo-200">
                    {{ $pendingRequestsCount }}
                </span>
            @endif
        </a>
    </div>
</div>
