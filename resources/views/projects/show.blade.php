@extends('layouts.dashboard')

@section('content')
<div x-data="projectDetail()" class="min-h-screen bg-[#F8F9FB] pb-20">
    
    <div class="fixed left-6 top-1/2 -translate-y-1/2 z-40 hidden lg:flex flex-col gap-4 bg-[#1A1A1A] p-3 rounded-2xl shadow-2xl border border-white/10">
        @auth
            <button class="p-3 hover:bg-white/10 rounded-xl transition text-white group" title="Like">
                <i class="fa-regular fa-heart text-xl group-hover:text-pink-500"></i>
                <span class="block text-[10px] text-center mt-1 text-gray-400">{{ $project->detail->like_count ?? 0 }}</span>
            </button>
        @endauth
        
        <button class="p-3 hover:bg-white/10 rounded-xl transition text-white group" title="Share">
            <i class="fa-solid fa-share-nodes text-xl group-hover:text-blue-400"></i>
        </button>

        @auth
            <button class="p-3 hover:bg-white/10 rounded-xl transition text-white group" title="Save Bookmark">
                <i class="fa-regular fa-bookmark text-xl group-hover:text-yellow-400"></i>
            </button>

            @if($isOwner)
                <div class="h-[1px] bg-white/10 mx-2 my-1"></div>
                <a href="{{ route('projects.edit', $project) }}" class="p-3 hover:bg-white/10 rounded-xl transition text-white group" title="Edit Project">
                    <i class="fa-regular fa-pen-to-square text-xl group-hover:text-green-400"></i>
                </a>
                <button @click="confirmDelete()" class="p-3 hover:bg-white/10 rounded-xl transition text-white group" title="Delete Project">
                    <i class="fa-regular fa-trash-can text-xl group-hover:text-red-500"></i>
                </button>
            @endif
        @endauth
    </div>

    <div class="max-w-7xl mx-auto px-6 pt-10">
        
        <div class="mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 tracking-tight">{{ $project->name }}</h1>
            <div class="flex items-center justify-center gap-3">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-600 border border-indigo-200">
                    {{ $project->detail->field->name }}
                </span>
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-600 border border-emerald-200">
                    {{ $project->detail->status->name }}
                </span>
                <div class="flex items-center gap-1 text-gray-500 ml-4">
                    <i class="fa-solid fa-calendar-day"></i>
                    <span class="text-sm">{{ \Carbon\Carbon::parse($project->detail->start_date)->format('M Y') }} - {{ $project->detail->end_date ? \Carbon\Carbon::parse($project->detail->end_date)->format('M Y') : 'Present' }}</span>
                </div>
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

                        <button @click="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition items-center justify-center hidden md:flex">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button @click="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition items-center justify-center hidden md:flex">
                            <i class="fa-solid fa-chevron-right"></i>
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
                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-black rounded-full"></span>
                        Project Overview
                    </h3>
                    <div class="prose prose-lg max-w-none text-gray-600 text-justify leading-relaxed">
                        {!! nl2br(e($project->detail->description)) !!}
                    </div>

                    <div class="mt-12">
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">Status Progress</p>
                        <div class="flex flex-wrap gap-2">
                                <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium hover:border-black transition cursor-default">
                                    {{ $project->detail->progress->name }}
                                </span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">Tech Stack & Tools</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->detail->tools as $projectTool)
                                <span class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium hover:border-black transition cursor-default">
                                    {{ $projectTool->tool->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-sm border">
                    <h3 class="text-xl font-bold mb-6">Collaborators ({{ $project->detail->collaborators->count() }})</h3>
                    <div class="space-y-4">
                        @foreach($project->detail->collaborators as $access)
                        <div class="flex items-center justify-between p-4 rounded-2xl border border-transparent hover:border-gray-100 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . ($access->user->profile->photo_profile ?? 'default.jpg')) }}" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $access->user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $access->user->username }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg uppercase tracking-tight">
                                {{ $access->project_role }}
                            </span>
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
                            <button class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition flex items-center justify-center gap-2">
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

        alertVectra() {
            alert('Currently this feature is not available.');
        },

        confirmDelete() {
            if(confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
                // Submit delete form
            }
        }
    }
}
</script>

<style>
    /* Custom Scrollbar for better UI */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #999; }
</style>
@endsection