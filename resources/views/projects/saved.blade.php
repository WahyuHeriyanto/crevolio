@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 mb-20">
    
    {{-- HEADER SECTION --}}
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Saved Projects</h1>
        <p class="text-gray-500 mt-2">Projects you've bookmarked for later inspiration or collaboration.</p>
    </div>

    <div class="grid grid-cols-12 gap-10">
        {{-- MAIN LIST --}}
        <div class="col-span-12 lg:col-span-9">
            <div class="flex flex-col gap-6">
                @forelse ($projects as $project)
                    @include('profile.partials.project-item-saved', [
                        'project' => $project,
                        'isOwner' => false // Karena ini halaman saved, bukan kepemilikan
                    ])
                @empty
                    <div class="text-center py-20 bg-white rounded-[40px] border-2 border-dashed border-gray-100">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-regular fa-bookmark text-2xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No saved projects</h3>
                        <p class="text-gray-400 mt-1">Explore projects and save them to see them here.</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-6 px-6 py-3 bg-black text-white rounded-2xl text-sm font-semibold hover:bg-gray-800 transition">
                            Explore Projects
                        </a>
                    </div>
                @endforelse

                <div class="mt-10">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>

        {{-- SIDEBAR INFO (Optional) --}}
        <aside class="hidden lg:block lg:col-span-3">
            <div class="sticky top-24 p-6 bg-indigo-50 rounded-[32px] border border-indigo-100">
                <h4 class="font-bold text-indigo-900 mb-2">Quick Tip</h4>
                <p class="text-sm text-indigo-700 leading-relaxed">
                    Saving projects helps you keep track of creators you admire and styles you want to emulate in your future work.
                </p>
            </div>
        </aside>
    </div>
</div>
@endsection