<div class="bg-white/90 space-y-6">

    {{-- COVER --}}
    <div class="relative h-48 rounded-2xl overflow-hidden bg-gray-200">
        <img
            src="{{ $profile?->background_image
                ? asset('storage/' . $profile->background_image)
                : 'https://picsum.photos/1200/300' }}"
            class="w-full h-full object-cover"
        />
    </div>

    {{-- MAIN HEADER --}}
    <div class="bg-white/90 relative -mt-16 flex justify-between items-end gap-6 px-4">

        {{-- LEFT --}}
        <div class="flex items-end gap-6">

            {{-- AVATAR --}}
            <img
                src="{{ $profile?->photo_profile
                    ? asset('storage/' . $profile->photo_profile)
                    : 'https://i.pravatar.cc/150' }}"
                class="w-36 h-36 rounded-full border-4 border-white shadow-lg bg-white relative -top-10"
            />

            {{-- INFO CARD --}}
            <div class="bg-white/90 backdrop-blur rounded-xl px-6 py-4 shadow-sm max-w-xl">

                <h1 class="text-2xl font-semibold">
                    {{ $user->name }}
                </h1>

                <p class="text-gray-600 mt-1">
                    {{ $profile?->description ?? 'No description yet.' }}
                </p>

                {{-- FOLLOW STATS --}}
                <div class="flex gap-6 mt-4 text-sm text-gray-600">
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-black">
                            {{ $profile?->followers ?? 0 }}
                        </span>
                        <span>Followers</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-black">
                            {{ $profile?->following ?? 0 }}
                        </span>
                        <span>Following</span>
                    </div>
                </div>

                {{-- TAGS --}}
                <div class="flex gap-4 mt-4">

                    {{-- EXPERTISES --}}
                    @php
                        $expertises = $profile?->expertises ?? collect();
                    @endphp
                    <div class="flex flex-wrap gap-2">
                        @foreach ($expertises->take(3) as $item)
                            @if ($item->expertise)
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100">
                                    {{ $item->expertise->name }}
                                </span>
                            @endif
                        @endforeach

                        @if ($expertises->count() > 3)
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-200">
                                +{{ $expertises->count() - 3 }}
                            </span>
                        @endif
                    </div>

                    {{-- TOOLS --}}
                    @php
                        $tools = $profile?->tools ?? collect();
                    @endphp
                    <div class="flex flex-wrap gap-2">
                        @foreach ($tools->take(3) as $item)
                            @if ($item->tool)
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100">
                                    {{ $item->tool->name }}
                                </span>
                            @endif
                        @endforeach

                        @if ($tools->count() > 3)
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-200">
                                +{{ $tools->count() - 3 }}
                            </span>
                        @endif
                    </div>
                </div>
                {{-- SOCIAL MEDIA --}}
                <div class="flex gap-4 mt-4">
                    @foreach ($profile?->socialMedias ?? [] as $social)
                        @if ($social->category && $social->category->icon)
                            <a
                                href="{{ $social->link }}"
                                target="_blank"
                                class="hover:opacity-80 transition"
                            >
                                <img
                                    src="{{ asset('storage/' . $social->category->icon) }}"
                                    alt="{{ $social->category->name }}"
                                    class="w-8 h-8"
                                />
                            </a>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>

            {{-- RIGHT --}}
            <div class="flex items-center gap-3 mb-4 mr-6">

                @auth
                    @if(auth()->id() !== $user->id)
                        <button class="px-4 py-2 text-sm rounded-full border hover:bg-gray-100 transition">
                            Follow
                        </button>
                    @endif
                @endauth

                @auth
                    @if(auth()->id() === $user->id)

                        <a
                            x-show="tab === 'projects'"
                            href="#"
                            class="px-5 py-2 text-sm rounded-full bg-black text-white hover:bg-gray-800 transition"
                        >
                            + Create Project
                        </a>

                        <a
                            x-show="tab === 'portfolios'"
                            href="#"
                            class="px-5 py-2 text-sm rounded-full bg-black text-white hover:bg-gray-800 transition"
                        >
                            + Add Portfolio
                        </a>

                    @endif
                @endauth

            </div>

        </div>
    </div>
