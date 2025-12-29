@php
    $detail  = $project->detail;
    $status  = $detail?->status;
    $field   = $detail?->field;
    $tools   = $detail?->tools ?? collect();
    $collabs = $detail?->collaborators ?? collect();
    $cover   = $project->medias->first();
    // Cek status saved
    $isSaved = auth()->user()->saveds()->where('project_id', $project->id)->exists();
@endphp

<div
    class="relative bg-white rounded-2xl border hover:shadow-md transition overflow-hidden cursor-pointer group"
    onclick="window.location='{{ route('projects.show', $project->id) }}'"
    {{-- Alpine JS Data --}}
    x-data="{ 
        saved: {{ $isSaved ? 'true' : 'false' }},
        toggleSave() {
            fetch('{{ route('projects.save', $project->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.saved = data.status;
                // Jika di halaman saved, sembunyikan card saat di-unsave
                if(!this.saved && window.location.pathname.includes('saved')) {
                    $el.closest('.project-card-container')?.remove(); 
                    // Atau gunakan simple hide:
                    $el.style.display = 'none';
                }
            });
        }
    }"
>
    {{-- ACTION BUTTONS (Floating di pojok kanan atas) --}}
    <div class="absolute top-4 right-4 z-30 flex gap-2">
        {{-- BOOKMARK BUTTON --}}
        <button 
            @click.stop="toggleSave()" 
            type="button"
            class="bg-white/90 backdrop-blur-sm p-2.5 rounded-full shadow-sm border border-gray-100 transition hover:scale-110 flex items-center justify-center"
            :class="saved ? 'text-indigo-600' : 'text-gray-400 hover:text-black'"
        >
            <i :class="saved ? 'fa-solid fa-bookmark' : 'fa-regular fa-bookmark'" class="text-xl"></i>
        </button>

        {{-- EDIT ICON --}}
        @if ($isOwner)
            <a href="{{ route('projects.edit', $project->id) }}"
               class="bg-white/90 backdrop-blur-sm p-2.5 rounded-full shadow-sm border border-gray-100 hover:bg-gray-100 transition hover:scale-110 flex items-center justify-center"
               onclick="event.stopPropagation()">
                <i class="fa-solid fa-pen text-xl text-gray-700"></i>
            </a>
        @endif
    </div>

    {{-- MAIN CONTENT --}}
    <div class="relative z-10 flex flex-col md:flex-row gap-5 p-5 items-stretch">
        {{-- IMAGE --}}
        <div class="w-full md:w-80 h-48 md:h-60 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
            @if ($cover)
                <img src="{{ asset('storage/'.$cover->url) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                    <i class="fa-solid fa-image text-3xl text-gray-200"></i>
                </div>
            @endif
        </div>

        {{-- TEXT CONTENT --}}
        <div class="flex-1 space-y-3 pr-12"> {{-- pr-12 supaya text tidak tabrakan dengan floating button --}}
            {{-- STATUS --}}
            @if ($status)
                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full text-white
                    {{ $status->slug === 'open' ? 'bg-green-500' : 'bg-blue-500' }}">
                    {{ ucfirst($status->slug) }}
                </span>
            @endif

            <h3 class="text-lg font-bold leading-snug group-hover:text-indigo-600 transition">
                {{ $project->name }}
            </h3>

            <p class="text-sm text-gray-600 line-clamp-2">
                {{ $detail->description }}
            </p>

            {{-- META --}}
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400 font-medium">
                @if ($detail->start_date)
                    <span class="flex items-center gap-1">
                        <i class="fa-regular fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($detail->start_date)->format('M Y') }}
                    </span>
                @endif

                @if ($field)
                    <span class="px-2 py-1 bg-gray-100 rounded-md text-gray-600">
                        {{ $field->name }}
                    </span>
                @endif
            </div>

            {{-- TOOLS & COLLABS --}}
            <div class="pt-4 mt-2 border-t border-gray-50 flex items-center justify-between">
                {{-- COLLABORATORS --}}
                <div class="flex -space-x-2">
                    @foreach ($collabs->take(5) as $access)
                        @php $u = $access->user; @endphp
                        @if($u)
                        <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 overflow-hidden" title="{{ $u->name }}">
                            @if($u->profile?->photo_profile)
                                <img src="{{ asset('storage/'.$u->profile->photo_profile) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] font-bold bg-indigo-100 text-indigo-600">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- TOOLS --}}
                <div class="flex gap-2">
                    @foreach ($tools->take(4) as $tool)
                        <span class="px-2 py-1 bg-black text-white text-[10px] font-bold rounded-md">
                            {{ $tool->tool->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>