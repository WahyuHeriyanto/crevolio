@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6">

    {{-- HEADER --}}
    @include('profile.partials.header', ['user' => $user])

    {{-- TABS + CONTENT --}}
    <div x-data="{ tab: 'projects' }" class="mt-10">

        @include('profile.partials.tabs')

        {{-- PROJECTS --}}
        <div x-show="tab === 'projects'" class="flex flex-col gap-6 w-full mb-10">
            @forelse ($projects as $project)
                @include('profile.partials.project-item', [
                    'project' => $project,
                    // Tombol edit hanya muncul jika yang login adalah owner project tersebut
                    'isOwner' => auth()->check() && auth()->id() === $project->owner_id,
                ])
            @empty
                <div class="text-center py-10 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                    <p class="text-gray-400">No projects yet.</p>
                </div>
            @endforelse
        </div>

        {{-- PORTFOLIOS --}}
        <div x-show="tab === 'portfolios'" class="w-full max-w-5xl mx-auto mb-24 px-2">
            {{-- Tombol Export --}}
            @if($isOwner)
        <div class="flex justify-between items-center mb-8 px-2">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-indigo-600 transition-all shadow-lg shadow-gray-200">
                    <i class="fa-solid fa-file-export"></i>
                    <span>Export PDF</span>
                </button>

                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                    <a href="{{ route('profile.export', [$user->username, 'theme' => 'classic']) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-sm text-gray-700">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <p class="font-bold">Classic Timeline</p>
                            <p class="text-xs text-gray-400">Clean & Professional</p>
                        </div>
                    </a>
                    <a href="{{ route('profile.export', [$user->username, 'theme' => 'modern']) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition text-sm text-gray-700 border-t border-gray-50">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-grip"></i>
                        </div>
                        <div>
                            <p class="font-bold">Modern Grid</p>
                            <p class="text-xs text-gray-400">Visual & Bold</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
            <div class="relative pt-8">
                {{-- Garis Timeline --}}
                <div class="absolute left-[1.125rem] md:left-[2.125rem] top-0 bottom-0 w-[2px] bg-gradient-to-b from-transparent via-gray-200 to-transparent"></div>

                <div class="space-y-10">
                    @forelse ($portfolios as $portfolio)
                        @include('profile.partials.portfolio-item', ['portfolio' => $portfolio])
                    @empty
                        {{-- Empty state --}}
                        <div class="ml-16 md:ml-28 py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <i class="fa-solid fa-briefcase text-gray-200 text-3xl"></i>
                            </div>
                            <h4 class="text-gray-900 font-bold">No portfolio yet</h4>
                            <p class="text-gray-400 text-sm mt-1 max-w-xs">Add experience with projects and it will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
