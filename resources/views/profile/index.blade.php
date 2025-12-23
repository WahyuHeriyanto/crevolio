@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6">

    {{-- HEADER --}}
    @include('profile.partials.header', ['user' => $user])

    {{-- TABS + CONTENT --}}
    <div x-data="{ tab: 'projects' }" class="mt-10">

        @include('profile.partials.tabs')

        {{-- PROJECTS --}}
        <div x-show="tab === 'projects'" class="space-y-6">
            @for ($i = 0; $i < 3; $i++)
                @include('profile.partials.project-item')
            @endfor
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
