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
                    @click="tab = 'projects'"
                    :class="tab === 'projects'
                        ? 'text-black border-b-2 border-black pb-3'
                        : 'text-gray-400 hover:text-black pb-3'"
                >
                    Projects
                </button>

                <button
                    @click="tab = 'collaborators'"
                    :class="tab === 'collaborators'
                        ? 'text-black border-b-2 border-black pb-3'
                        : 'text-gray-400 hover:text-black pb-3'"
                >
                    Collaborators
                </button>
            </div>

            {{-- PROJECTS FEED --}}
            <div
                x-show="tab === 'projects'"
                x-transition
                class="space-y-6"
            >
                @for ($i = 0; $i < 5; $i++)
                    @include('dashboard.partials.project-card')
                @endfor
            </div>

            {{-- COLLABORATORS FEED --}}
            <div
                x-show="tab === 'collaborators'"
                x-transition
                class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                @for ($i = 0; $i < 6; $i++)
                    @include('dashboard.partials.collaborator-card')
                @endfor
            </div>

        </section>

    </div>
</div>
@endsection
