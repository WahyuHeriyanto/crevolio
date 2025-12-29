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
                    <h4 class="text-gray-900 font-bold">Belum ada portfolio</h4>
                    <p class="text-gray-400 text-sm mt-1 max-w-xs">Pengalaman kerja atau proyek mandiri akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

    </div>
</div>
@endsection
