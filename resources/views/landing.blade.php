<x-public-layout>

<section
    class="space-bg min-h-screen w-full flex items-center justify-center text-center px-6 text-white overflow-hidden relative"
    x-data="typingHero()"
    x-init="start()"
>
    {{-- Background Layer --}}
    <div class="stars-container"></div>
    
    {{-- Ornament Nebula --}}
    <div class="absolute top-0 -left-20 w-96 h-96 bg-indigo-600/10 rounded-full blur-[150px]"></div>
    <div class="absolute bottom-0 -right-20 w-96 h-96 bg-purple-600/10 rounded-full blur-[150px]"></div>

    <div class="max-w-3xl w-full relative z-10 mt-10">
        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-[10px] font-bold tracking-[0.2em] uppercase mb-8">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            Crevolio Ecosystem
        </div>

        <h1 class="text-5xl md:text-7xl font-black tracking-tighter mb-6 leading-tight bg-clip-text text-transparent bg-gradient-to-b from-white via-white to-gray-500">
            <span x-text="displayText"></span>
            <span class="border-r-4 border-indigo-500 animate-pulse ml-1"></span>
        </h1>

        <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
            Find people worth building with. Discover inspiring projects and connect with creators.
        </p>

        <a href="{{ route('login') }}"
           class="inline-block px-10 py-4 rounded-2xl bg-white text-black text-base font-black hover:bg-indigo-500 hover:text-white transition-all transform hover:scale-110 shadow-[0_0_30px_rgba(255,255,255,0.2)]">
            Get Started
        </a>
    </div>
</section>

{{-- MAIN EXPLORE SECTION (ID: features) --}}
<div id="features" class="bg-gray-50 py-24 relative scroll-mt-10">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- SEARCH & FILTER BAR --}}
        <div class="bg-white p-4 rounded-3xl border border-gray-100 mb-16 shadow-sm relative z-20">
            <form action="{{ route('landing') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[250px] relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search for projects..."
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-black">
                    <div class="absolute left-3 top-3.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <select name="field" class="bg-gray-50 border-none rounded-2xl text-sm py-3 px-4 focus:ring-2 focus:ring-black cursor-pointer">
                    <option value="">All Fields</option>
                    @isset($fields)
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}" {{ request('field') == $field->id ? 'selected' : '' }}>{{ $field->name }}</option>
                        @endforeach
                    @endisset
                </select>

                <button type="submit" class="bg-black text-white px-8 py-3 rounded-2xl text-sm font-medium hover:bg-gray-800 transition">
                    Search
                </button>
            </form>
        </div>

        {{-- PROJECT GRID --}}
        <div class="relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($projects as $project)
                    <div class="relative bg-white rounded-[32px] p-5 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-500 group cursor-pointer"
                         onclick="window.location.href='{{ route('login') }}'">
                        
                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $project->owner->profile->photo_profile ? asset('storage/' . $project->owner->profile->photo_profile) : 'https://ui-avatars.com/api/?name='.urlencode($project->owner->name) }}" 
                                     class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <span class="font-bold text-gray-900 text-sm block">{{ $project->owner->name }}</span>
                                    <div class="text-[10px] text-gray-400 uppercase tracking-tight">{{ $project->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="p-2 rounded-full text-gray-300 hover:text-indigo-600 transition-colors">
                                    <i class="fa-regular fa-bookmark"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Image & Tools --}}
                        <div class="relative mb-4 overflow-hidden rounded-[24px] aspect-[16/9] bg-gray-50">
                            <img src="{{ $project->medias->first() ? asset('storage/' . $project->medias->first()->url) : 'https://ui-avatars.com/api/?name='.urlencode($project->name).'&size=512' }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute bottom-3 left-3 flex flex-wrap gap-2">
                                @foreach($project->detail->tools->take(2) as $projectTool)
                                    <span class="bg-black/50 backdrop-blur-md text-white text-[9px] px-2.5 py-1 rounded-lg border border-white/20">
                                        {{ $projectTool->tool->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="mb-4">
                            <h3 class="font-black text-lg text-gray-900 line-clamp-1 group-hover:text-indigo-600 transition">{{ $project->name }}</h3>
                            <p class="text-gray-500 text-[11px] line-clamp-2 mt-1">{{ $project->detail->description }}</p>
                        </div>

                        {{-- Footer: Like & Collab --}}
                        <div class="flex items-center justify-between border-t border-gray-50 pt-4">
                            <div class="flex items-center gap-4">
                                {{-- Like Button (Visual Only) --}}
                                <div class="flex items-center gap-2 group/like">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center bg-gray-50 text-gray-400 group-hover/like:bg-indigo-50 group-hover/like:text-indigo-600 transition-colors">
                                        <i class="fa-solid fa-thumbs-up text-xs"></i>
                                    </div>
                                    <span class="text-xs font-black text-gray-600">{{ $project->likes_count }}</span>
                                </div>

                                {{-- Collaborators --}}
                                <div class="flex -space-x-3 overflow-hidden">
                                    @foreach($project->detail->collaborators->take(3) as $collab)
                                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white object-cover" 
                                             src="{{ $collab->user->profile->photo_profile ? asset('storage/' . $collab->user->profile->photo_profile) : 'https://ui-avatars.com/api/?name='.urlencode($collab->user->name) }}">
                                    @endforeach
                                    @if($project->detail->collaborators->count() > 3)
                                        <div class="flex items-center justify-center h-7 w-7 rounded-full bg-gray-900 text-[9px] font-bold text-white ring-2 ring-white">
                                            +{{ $project->detail->collaborators->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 text-gray-400">No projects found.</div>
                @endforelse
            </div>

            {{-- Fade Overlay --}}
            <div class="absolute bottom-0 left-0 w-full h-80 bg-gradient-to-t from-gray-50 via-gray-50/90 to-transparent z-10 pointer-events-none"></div>
        </div>

        {{-- CALL TO ACTION BOTTOM --}}
        <div class="mt-16 text-center relative z-20">
            <h3 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">Want to see more projects?</h3>
            <p class="text-gray-500 mb-10 max-w-lg mx-auto">Join our community to explore more projects, bookmark your favorites, and connect with creators.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('login') }}" class="group inline-flex items-center gap-3 px-10 py-4 bg-black text-white rounded-full font-bold hover:bg-indigo-600 hover:scale-105 transition-all duration-300">
                    <span>Login for view more</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="font-bold text-gray-900 hover:text-indigo-600 transition border-b-2 border-transparent hover:border-indigo-600 pb-1">Create an account</a>
            </div>
        </div>
    </div>
</div>

</x-public-layout>