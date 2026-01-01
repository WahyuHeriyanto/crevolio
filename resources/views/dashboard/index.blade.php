@extends('layouts.dashboard')

@section('content')
<div
    class="max-w-7xl mx-auto px-6"
    x-data="{ tab: 'projects' }"
>
    <div class="grid grid-cols-12 gap-10">

        {{-- LEFT SIDEBAR --}}
        <aside class="col-span-12 lg:col-span-3">
            <div class="sticky top-24">
                @include('dashboard.partials.context-panel')
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <section class="col-span-12 lg:col-span-9">

            {{-- TABS --}}
            <div class="flex gap-8 border-b mb-8 text-sm font-medium">
                <button
                    @click="tab ='projects'; $store.dashboard.setTab('projects')"
                    :class="tab === 'projects'
                        ? 'text-black border-b-2 border-black pb-3'
                        : 'text-gray-400 hover:text-black pb-3'"
                >
                    Projects
                </button>

                <button
                    @click="tab = 'collaborators'; $store.dashboard.setTab('collaborators')"
                    :class="tab === 'collaborators'
                        ? 'text-black border-b-2 border-black pb-3'
                        : 'text-gray-400 hover:text-black pb-3'"
                >
                    Crevolians
                </button>
            </div>

            {{-- PROJECTS FEED --}}
            <div x-show="tab === 'projects'" x-transition class="space-y-8">
                <div id="project-container" class="space-y-8">
                    @include('dashboard.partials.load-projects')
                </div>
            </div>
            {{-- COLLABORATORS FEED --}}
            <div
                x-show="tab === 'collaborators'"
                x-transition
                class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"
                id="crevolian-container"
            >
                @include('dashboard.partials.load-crevolians')
            </div>

            {{-- LOADING INDICATOR GLOBAL (Pindahkan ke luar div tab agar terlihat di keduanya) --}}
            <div id="loading-state" class="hidden py-10 text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-600"></div>
                <p class="text-gray-500 text-sm mt-2">Loading more projects...</p>
            </div>
        </section>
    </div>
</div>
@endsection

<script>
let projectNextPage = '{{ $projects->nextPageUrl() }}';
let crevolianNextPage = '{{ $crevolians->nextPageUrl() }}';

let loading = false;
let currentTab = 'projects';

document.addEventListener('alpine:init', () => {
    Alpine.store('dashboard', {
        setTab(tab) {
            currentTab = tab;
        }
    });
});

window.addEventListener('scroll', () => {
    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 120) {
        if (loading) return;

        if (currentTab === 'projects' && projectNextPage) {
            loadMore(projectNextPage, 'projects');
        }

        if (currentTab === 'collaborators' && crevolianNextPage) {
            loadMore(crevolianNextPage, 'crevolians');
        }
    }
});

function loadMore(url, type) {
    loading = true;
    document.getElementById('loading-state').classList.remove('hidden');

    fetch(url + '&type=' + type, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (type === 'projects') {
            projectNextPage = data.nextPage;
            document
                .getElementById('project-container')
                .insertAdjacentHTML('beforeend', data.html);
        }

        if (type === 'crevolians') {
            crevolianNextPage = data.nextPage;
            document
                .getElementById('crevolian-container')
                .insertAdjacentHTML('beforeend', data.html);
        }

        loading = false;
        document.getElementById('loading-state').classList.add('hidden');
    })
    .catch(() => {
        loading = false;
        document.getElementById('loading-state').classList.add('hidden');
    });
}
</script>
