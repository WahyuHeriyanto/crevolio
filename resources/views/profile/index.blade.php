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
        <div x-show="tab === 'portfolios'" class="grid md:grid-cols-2 gap-6">
            @for ($i = 0; $i < 4; $i++)
                @include('profile.partials.portfolio-item')
            @endfor
        </div>

    </div>
</div>
@endsection
