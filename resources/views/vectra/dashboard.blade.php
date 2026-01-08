@extends('vectra.layouts.app')

@section('content')

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Your Projects</h1>
    <p class="text-gray-500 mt-1">Manage and collaborate on your active projects</p>
</div>

{{-- Container Grid - Kasih ID supaya gampang ditembak script --}}
<div id="project-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pb-12">
    @forelse ($projects as $project)
        @php
            $detail  = $project->detail;
            $status  = $detail?->status;
            $field   = $detail?->field;
            $cover   = $project->medias->first();
            $collabs = $detail?->collaborators ?? collect();
        @endphp

        <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 cursor-pointer flex flex-col">
            <div class="relative aspect-video bg-gray-100 shrink-0 overflow-hidden">
                @if($cover)
                    <img src="{{ asset('storage/'.$cover->url) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" />
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No Preview</div>
                @endif

                @if($status)
                    <span class="absolute top-3 left-3 px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full text-white shadow-sm {{ $status->slug === 'open' ? 'bg-emerald-500' : 'bg-gray-500' }}">
                        {{ $status->name }}
                    </span>
                @endif
            </div>

            <div class="p-5 flex flex-col flex-1 justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg leading-tight line-clamp-2">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $field?->name ?? 'General Project' }}</p>
                </div>

                <div class="flex items-center justify-between pt-5 mt-auto">
                    <div class="flex -space-x-2">
                        @foreach($collabs->take(5) as $access)
                            <img src="{{ $access->user->profile->photo_profile ? asset('storage/'.$access->user->profile->photo_profile) : asset('assets/images/photo-profile-default.png') }}" 
                                 class="w-8 h-8 rounded-full border-2 border-white object-cover shadow-sm" />
                        @endforeach
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $collabs->count() }} members</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center text-gray-400 font-medium">No projects found.</div>
    @endforelse
</div>

{{-- Loading Indicator --}}
<div id="loading-state" class="hidden py-10 text-center">
    <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-black"></div>
    <p class="text-gray-500 text-xs mt-2 font-bold uppercase">Loading more...</p>
</div>

<script>
    let nextPageUrl = '{{ $projects->nextPageUrl() }}';
    let loading = false;

    // TARGET KITA SEKARANG ADALAH ID #main-content
    const scrollContainer = document.getElementById('main-content');

    if (scrollContainer) {
        scrollContainer.addEventListener('scroll', () => {
            // Logika scroll di dalem div
            if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight - 100) {
                if (!loading && nextPageUrl) {
                    loadMoreProjects();
                }
            }
        });
    }

    function loadMoreProjects() {
        loading = true;
        document.getElementById('loading-state').classList.remove('hidden');

        fetch(nextPageUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Append hasil kartu dari controller
            document.getElementById('project-container').insertAdjacentHTML('beforeend', data.html);
            nextPageUrl = data.nextPage;
            loading = false;
            document.getElementById('loading-state').classList.add('hidden');
        })
        .catch(err => {
            console.error("Gagal load:", err);
            loading = false;
            document.getElementById('loading-state').classList.add('hidden');
        });
    }
</script>
@endsection