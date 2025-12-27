@php
    $detail  = $project->detail;
    $status  = $detail?->status;
    $field   = $detail?->field;
    $tools   = $detail?->tools ?? collect();
    $collabs = $detail?->collaborators ?? collect();
    $cover   = $project->medias->first();
@endphp

<div
    class="relative bg-white rounded-2xl border hover:shadow-md transition overflow-hidden cursor-pointer"
    onclick="window.location='{{ route('projects.show', $project->id) }}'"
>
    <div class="relative bg-white rounded-2xl border hover:shadow-md transition overflow-hidden">
        {{-- EDIT ICON --}}
        @if ($isOwner)
            <div class="absolute top-3 right-3 z-20 pointer-events-auto">
                <a href="{{ route('projects.edit', $project->id) }}"
                class="bg-white p-2 rounded-full shadow hover:bg-gray-100"
                onclick="event.stopPropagation()">
                    ✏️
                </a>
            </div>
        @endif

        {{-- CONTENT --}}
        <div class="relative z-10 flex gap-5 p-5 items-stretch">

            {{-- IMAGE --}}
            <div class="w-80 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                @if ($cover)
                    <img src="{{ asset('storage/'.$cover->url) }}"
                        class="w-full h-full object-cover">
                @endif
            </div>

            {{-- TEXT CONTENT --}}
            <div class="flex-1 space-y-3">

                {{-- STATUS --}}
                @if ($status)
                    <span class="inline-block px-3 py-1 text-xs rounded-full text-white
                        {{ $status->slug === 'open' ? 'bg-green-500' : 'bg-blue-500' }}">
                        {{ ucfirst($status->slug) }}
                    </span>
                @endif

                {{-- TITLE --}}
                <h3 class="text-lg font-semibold leading-snug">
                    {{ $project->name }}
                </h3>

                {{-- DESCRIPTION --}}
                <p class="text-sm text-gray-600 line-clamp-2">
                    {{ $detail->description }}
                </p>

                {{-- META --}}
                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                    @if ($detail->start_date)
                        <span>
                            {{ \Carbon\Carbon::parse($detail->start_date)->format('M Y') }}
                            –
                            {{ $detail->end_date
                                ? \Carbon\Carbon::parse($detail->end_date)->format('M Y')
                                : 'Present' }}
                        </span>
                    @endif

                    @if ($field)
                        <span class="px-2 py-1 bg-gray-100 rounded-full">
                            {{ $field->name }}
                        </span>
                    @endif
                </div>

                {{-- TOOLS --}}
                @if ($tools->isNotEmpty())
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">
                            Tools Used
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tools->take(4) as $tool)
                                <span class="px-3 py-1 text-xs rounded-full bg-black text-white">
                                    {{ $tool->tool->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- COLLABORATORS --}}
                @if ($collabs->isNotEmpty())
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">
                            Collaborators
                        </p>

                        <div class="flex -space-x-2">
                            @foreach ($collabs as $access)
                                @php
                                    $user  = $access->user;
                                    $photo = $user?->profile?->photo_profile;
                                @endphp

                                @if ($user)
                                    <a href="{{ route('profile.show', $user->username) }}"
                                    title="{{ $user->name }}"
                                    class="w-8 h-8 rounded-full border-2 border-white overflow-hidden bg-gray-200 pointer-events-auto z-20"
                                    onclick="event.stopPropagation()">
                                        @if ($photo)
                                            <img src="{{ asset('storage/'.$photo) }}"
                                                class="w-full h-full object-cover">
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
