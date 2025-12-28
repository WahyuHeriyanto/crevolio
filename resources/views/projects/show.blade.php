@extends('layouts.dashboard')

@section('content')
<div x-data="projectDetail()" class="min-h-screen bg-[#F8F9FB] pb-20">
    
    <div class="fixed left-6 top-1/2 -translate-y-1/2 z-40 hidden lg:flex flex-col gap-4 bg-[#1A1A1A] w-20 py-8 items-center rounded-[40px] shadow-2xl border border-white/10">
        @auth
            <button class="relative group flex flex-col items-center gap-1 transition text-white" title="Like">
                <div class="p-3 group-hover:bg-white/10 rounded-2xl transition">
                    <i class="fa-solid fa-thumbs-up text-2xl group-hover:text-blue-400"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $project->detail->like_count ?? 0 }}</span>
                <span class="absolute left-full ml-4 px-3 py-1 bg-black text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Like Project</span>
            </button>
        @endauth
        
        <button class="relative group p-4 hover:bg-white/10 rounded-2xl transition text-white" title="Share">
            <i class="fa-solid fa-share text-2xl group-hover:text-emerald-400"></i>
            <span class="absolute left-full ml-4 px-3 py-1 bg-black text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Share</span>
        </button>

        @auth
            <button class="relative group p-4 hover:bg-white/10 rounded-2xl transition text-white" title="Save Bookmark">
                <i class="fa-solid fa-bookmark text-2xl group-hover:text-yellow-400"></i>
                <span class="absolute left-full ml-4 px-3 py-1 bg-black text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Save</span>
            </button>

            @if($isOwner)
                <div class="h-[1px] w-8 bg-white/10 my-2"></div>
                <a href="{{ route('projects.edit', $project) }}" class="relative group p-4 hover:bg-white/10 rounded-2xl transition text-white" title="Edit Project">
                    <i class="fa-solid fa-pen text-2xl group-hover:text-indigo-400"></i>
                    <span class="absolute left-full ml-4 px-3 py-1 bg-black text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Edit</span>
                </a>
                <button @click="confirmDelete()" class="relative group p-4 hover:bg-white/10 rounded-2xl transition text-white" title="Delete Project">
                    <i class="fa-solid fa-trash-can text-2xl group-hover:text-red-500"></i>
                    <span class="absolute left-full ml-4 px-3 py-1 bg-black text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Delete</span>
                </button>
            @endif
        @endauth
    </div>

    <div class="max-w-7xl mx-auto px-6 pt-10 lg:pl-32">
        
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">{{ $project->name }}</h1>
            <div class="flex items-center justify-center gap-3">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-600 border border-indigo-200">
                    {{ $project->detail->field->name }}
                </span>
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-600 border border-emerald-200">
                    {{ $project->detail->status->name }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                <div class="relative bg-white rounded-[32px] p-2 shadow-sm border overflow-hidden group">
                    <div class="relative h-[300px] md:h-[500px] rounded-[28px] overflow-hidden bg-gray-100">
                        @foreach($project->medias as $index => $media)
                            <img 
                                x-show="activeImage === {{ $index }}"
                                src="{{ asset('storage/' . $media->url) }}" 
                                @click="fullscreen = true; fullsrc = '{{ asset('storage/' . $media->url) }}'"
                                class="w-full h-full object-cover cursor-zoom-in transition-all duration-500"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                            >
                        @endforeach

                        <button @click="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur rounded-full shadow-lg opacity-60 group-hover:opacity-100 transition flex items-center justify-center text-black hover:bg-white z-10">
                            <i class="fa-solid fa-chevron-left text-xl"></i>
                        </button>
                        <button @click="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur rounded-full shadow-lg opacity-60 group-hover:opacity-100 transition flex items-center justify-center text-black hover:bg-white z-10">
                            <i class="fa-solid fa-chevron-right text-xl"></i>
                        </button>
                    </div>

                    <div class="flex justify-center gap-2 mt-4 pb-2">
                        @foreach($project->medias as $index => $media)
                            <button 
                                @click="activeImage = {{ $index }}"
                                :class="activeImage === {{ $index }} ? 'w-8 bg-black' : 'w-2 bg-gray-300'"
                                class="h-2 rounded-full transition-all duration-300"
                            ></button>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-sm border">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <h3 class="text-xl font-bold flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-black rounded-full"></span>
                            Project Overview
                        </h3>
                        
                        <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fa-solid fa-calendar-day text-indigo-500"></i>
                            <span class="text-sm font-semibold text-gray-600">
                                {{ \Carbon\Carbon::parse($project->detail->start_date)->format('d M Y') }} 
                                - 
                                {{ $project->detail->end_date ? \Carbon\Carbon::parse($project->detail->end_date)->format('d M Y') : 'Present' }}
                            </span>
                        </div>
                    </div>

                    <div class="prose prose-lg max-w-none text-gray-600 text-justify leading-relaxed">
                        {!! nl2br(e($project->detail->description)) !!}
                    </div>

                    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Status Progress</p>
                            <span class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-xl text-sm font-bold uppercase tracking-tight">
                                {{ $project->detail->progress->name ?? 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tech Stack & Tools</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($project->detail->tools as $projectTool)
                                    <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium hover:border-black transition cursor-default">
                                        {{ $projectTool->tool->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-sm border">
                    <h3 class="text-xl font-bold mb-6">Collaborators ({{ $project->detail->collaborators->count() }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($project->detail->collaborators as $access)
                        <div class="flex items-center justify-between p-4 rounded-2xl border border-transparent hover:border-gray-100 hover:bg-gray-50 transition group">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . ($access->user->profile->photo_profile ?? 'default.jpg')) }}" class="w-12 h-12 rounded-full object-cover shadow-sm group-hover:scale-105 transition">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $access->user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $access->project_role }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-6">
                    <button @click="alertVectra()" class="flex-1 py-4 px-8 rounded-2xl bg-gray-200 text-gray-500 font-bold hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-rocket"></i>
                        Run Crevolio Vectra 1.0
                    </button>

                    @auth
                        @if(!$isOwner)
                            @if(!$isCollaborator)
                                <form action="{{ route('projects.join', $project) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-4 px-8 rounded-2xl bg-black text-white font-bold hover:bg-gray-800 transition-all shadow-lg hover:shadow-black/20">
                                        Join Project
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('projects.leave', $project) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full py-4 px-8 rounded-2xl border-2 border-red-500 text-red-500 font-bold hover:bg-red-50 transition-all">
                                        Leave Project
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[32px] p-8 shadow-sm border sticky top-10">
                    <div class="text-center" x-ref="profileCard">
                        <div class="relative inline-block mb-4">
                            <img :src="activeProfile.photo" class="w-24 h-24 rounded-full border-4 border-white shadow-xl object-cover">
                            <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900" x-text="activeProfile.name"></h3>
                        <p class="text-sm text-gray-500 mb-6" x-text="activeProfile.email"></p>
                        
                        <div class="grid grid-cols-2 gap-4 py-4 border-y border-gray-100 mb-6">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Followers</p>
                                <p class="text-lg font-black text-gray-900" x-text="activeProfile.followers"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">Following</p>
                                <p class="text-lg font-black text-gray-900" x-text="activeProfile.following"></p>
                            </div>
                        </div>

                        @auth
                            <button 
                                x-show="activeProfile.id !== {{ auth()->id() }}" 
                                class="w-full py-4 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-200"
                            >
                                <i class="fa-solid fa-user-plus text-sm"></i>
                                Follow
                            </button>
                        @endauth

                        <div class="mt-10 text-left">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Other Collaborators</p>
                            <div class="flex -space-x-3 overflow-hidden">
                                @foreach($project->detail->collaborators as $access)
                                    <img 
                                        @click="switchProfile({{ json_encode([
                                            'id' => $access->user->id,
                                            'name' => $access->user->name,
                                            'email' => $access->user->email,
                                            'photo' => asset('storage/' . ($access->user->profile->photo_profile ?? 'default.jpg')),
                                            'followers' => $access->user->profile->followers ?? 0,
                                            'following' => $access->user->profile->following ?? 0,
                                        ]) }})"
                                        src="{{ asset('storage/' . ($access->user->profile->photo_profile ?? 'default.jpg')) }}" 
                                        class="inline-block h-10 w-10 rounded-full ring-2 ring-white cursor-pointer hover:scale-110 hover:z-10 transition object-cover"
                                    >
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template x-if="fullscreen">
        <div class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 md:p-10" @keydown.escape.window="fullscreen = false">
            <button @click="fullscreen = false" class="absolute top-10 right-10 text-white text-4xl hover:rotate-90 transition-transform duration-300">×</button>
            <img :src="fullsrc" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </template>

</div>

<script>
function projectDetail() {
    return {
        activeImage: 0,
        totalImages: {{ $project->medias->count() }},
        fullscreen: false,
        fullsrc: '',
        activeProfile: {
            id: {{ $project->owner->id }},
            name: '{{ $project->owner->name }}',
            email: '{{ $project->owner->email }}',
            photo: '{{ asset('storage/' . ($project->owner->profile->photo_profile ?? 'default.jpg')) }}',
            followers: {{ $project->owner->profile->followers ?? 0 }},
            following: {{ $project->owner->profile->following ?? 0 }}
        },
        
        nextImage() {
            this.activeImage = (this.activeImage + 1) % this.totalImages;
        },
        
        prevImage() {
            this.activeImage = (this.activeImage - 1 + this.totalImages) % this.totalImages;
        },

        switchProfile(data) {
            this.activeProfile = data;
        },

        confirmDelete() {
            if(confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
                // Submit delete logic here
                console.log('Project deleted');
            }
        },

        alertVectra() {
            alert('Launching Crevolio Vectra 1.0 (Beta)...');
        }
    }
}
</script>
@endsection