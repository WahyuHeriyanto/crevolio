@extends('layouts.dashboard')

@section('content')
<div
    class="max-w-7xl mx-auto px-6"
    x-data="{ tab: '{{ $activeTab === 'collaborators' ? 'collaborators' : 'projects' }}' }"
    x-init="$store.dashboard.setTab(tab)"
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
            {{-- SEARCH & FILTER BAR --}}
            <div class="bg-white p-4 rounded-3xl border border-gray-100 mb-6 shadow-sm">
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <input type="hidden" name="tab" :value="tab === 'collaborators' ? 'collaborators' : 'projects'">    
                    {{-- Search Bar --}}
                    <div class="flex-1 min-w-[200px] relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            :placeholder="tab === 'projects' ? 'Search projects title...' : 'Search Crevolians name...'"
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-black">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Dropdown Filter Project Field (Hanya Project) --}}
                    <template x-if="tab === 'projects'">
                        <select name="field" class="bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-black">
                            <option value="">All Fields</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}" {{ request('field') == $field->id ? 'selected' : '' }}>{{ $field->name }}</option>
                            @endforeach
                        </select>
                    </template>

                    {{-- Dropdown Filter Expertises (Hanya Crevolians) --}}
                    <template x-if="tab === 'collaborators'">
                        <select name="expertise" class="bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-black">
                            <option value="">All Expertises</option>
                            @foreach($expertises as $exp)
                                <option value="{{ $exp->id }}" {{ request('expertise') == $exp->id ? 'selected' : '' }}>{{ $exp->name }}</option>
                            @endforeach
                        </select>
                    </template>

                    {{-- Only Open Checklist (Hanya Project) --}}
                    <template x-if="tab === 'projects'">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="only_open" value="true" {{ request('only_open') == 'true' ? 'checked' : '' }}
                                class="rounded border-gray-300 text-black focus:ring-black">
                            <span class="text-sm font-medium">Only Open Status</span>
                        </label>
                    </template>

                    <button type="submit" class="bg-black text-white px-6 py-2 rounded-2xl text-sm font-medium hover:bg-gray-800 transition">
                        Filter
                    </button>
                    <a href="{{ route('dashboard') }}" class="text-xs text-gray-400 hover:text-black underline">Reset</a>
                </form>
            </div>

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

    // Mengambil parameter pencarian dari URL saat ini agar filter tidak hilang saat scroll
    const currentUrlParams = new URLSearchParams(window.location.search);
    let fetchUrl = new URL(url);
    
    // Copy semua parameter dari URL browser (search, field, only_open, dll) ke URL Fetch
    currentUrlParams.forEach((value, key) => {
        if(key !== 'projects_page' && key !== 'crevolians_page') { // Kecuali page yang dihandle Laravel
            fetchUrl.searchParams.set(key, value);
        }
    });
    
    // Tambahkan parameter type
    fetchUrl.searchParams.set('type', type);

    fetch(fetchUrl.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (type === 'projects') {
            projectNextPage = data.nextPage;
            document.getElementById('project-container').insertAdjacentHTML('beforeend', data.html);
        }
        if (type === 'crevolians') {
            crevolianNextPage = data.nextPage;
            document.getElementById('crevolian-container').insertAdjacentHTML('beforeend', data.html);
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
