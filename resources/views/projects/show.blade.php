@extends('layouts.dashboard')

@section('content')
<div x-data="projectDetail()" class="min-h-screen bg-[#F8F9FB] pb-20">
    {{-- TOOLBAR --}}
    <div class="fixed left-6 top-1/2 -translate-y-1/2 z-40 hidden lg:flex flex-col gap-4 bg-[#1A1A1A] w-20 py-8 items-center rounded-[40px] shadow-2xl border border-white/10">
        @auth
            <button @click="toggleLike()" class="relative group flex flex-col items-center gap-1 transition" :class="isLiked ? 'text-blue-400' : 'text-white'">
                <div class="p-3 group-hover:bg-white/10 rounded-2xl transition">
                    <i class="fa-solid fa-thumbs-up text-2xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-tighter" x-text="likeCount"></span>
            </button>
        @endauth
        
        <button @click="showShareModal = true" class="relative group p-4 hover:bg-white/10 rounded-2xl transition text-white">
            <i class="fa-solid fa-share text-2xl group-hover:text-emerald-400"></i>
        </button>

        @auth
            <button @click="toggleSave()" class="relative group p-4 hover:bg-white/10 rounded-2xl transition" :class="isSaved ? 'text-yellow-400' : 'text-white'">
                <i class="fa-solid fa-bookmark text-2xl"></i>
            </button>

            @if($isOwner)
                <div class="h-[1px] w-8 bg-white/10 my-2"></div>
                {{-- TOMBOL JOIN REQUESTS --}}
                <a href="{{ route('projects.requests') }}" class="relative group p-4 hover:bg-white/10 rounded-2xl transition text-white">
                    <i class="fa-solid fa-users-viewfinder text-2xl group-hover:text-amber-400"></i>
                    
                    {{-- Badge Indikator Request Pending --}}
                    @if($pendingRequestsCount > 0)
                        <span class="absolute top-2 right-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-[#1A1A1A]">
                            {{ $pendingRequestsCount }}
                        </span>
                    @endif

                    {{-- Tooltip Simple --}}
                    <span class="absolute left-full ml-4 px-2 py-1 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                        Join Requests
                    </span>
                </a>
                <a href="{{ route('projects.edit', $project) }}" class="p-4 hover:bg-white/10 rounded-2xl transition text-white">
                    <i class="fa-solid fa-pen text-2xl group-hover:text-indigo-400"></i>
                </a>
                <button @click="confirmDelete()" class="p-4 hover:bg-white/10 rounded-2xl transition text-white">
                    <i class="fa-solid fa-trash-can text-2xl group-hover:text-red-500"></i>
                </button>
                {{-- Form Hidden untuk Delete --}}
                <form id="delete-project-form" action="{{ route('projects.destroy', $project) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
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

                    @php
                        $hasRequested = auth()->check() ? \App\Models\ProjectAccessRequest::where('project_id', $project->id)->where('requester_id', auth()->id())->first() : null;
                        $isOpen = $project->detail->status->slug === 'open';
                    @endphp
                    @auth
                        {{-- Jika bukan pemilik project --}}
                        @if(!$isOwner)
                            
                            {{-- KONDISI 1: Jika sudah jadi Collaborator (Munculkan tombol Leave, status project apapun) --}}
                            @if($isCollaborator)
                                <form action="{{ route('projects.leave', $project) }}" method="POST" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full py-4 px-8 rounded-2xl border-2 border-red-500 text-red-500 font-bold hover:bg-red-50 transition-all">
                                        Leave Project
                                    </button>
                                </form>

                            {{-- KONDISI 2: Jika sudah kirim Request (Munculkan tombol Requested, status project apapun) --}}
                            @elseif($hasRequested && $hasRequested->status === 'pending')
                                <button disabled class="flex-1 py-4 px-8 rounded-2xl bg-gray-100 text-gray-400 font-bold cursor-not-allowed">
                                    Requested
                                </button>

                            {{-- KONDISI 3: Belum Join & Belum Request (Hanya muncul jika status project 'open') --}}
                            @elseif($isOpen)
                                <form action="{{ route('projects.join', $project) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-4 px-8 rounded-2xl bg-black text-white font-bold hover:bg-gray-800 transition-all shadow-lg">
                                        Join Project
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
                            {{-- TOMBOL FOLLOW DINAMIS --}}
                            <button 
                                x-show="activeProfile.id !== {{ auth()->id() }}" 
                                @click="handleFollow()"
                                :disabled="isProcessing"
                                class="w-full py-4 rounded-2xl font-bold transition flex items-center justify-center gap-2 shadow-lg"
                                :class="{
                                    'bg-indigo-600 text-white hover:bg-indigo-700 shadow-indigo-200': activeProfile.followStatus === 'none',
                                    'bg-gray-200 text-gray-400 cursor-default': activeProfile.followStatus === 'requested',
                                    'bg-gray-200 text-gray-600 hover:bg-gray-300': activeProfile.followStatus === 'followed'
                                }"
                            >
                                <template x-if="activeProfile.followStatus === 'none'">
                                    <span><i class="fa-solid fa-user-plus text-sm mr-1"></i> Follow</span>
                                </template>
                                <template x-if="activeProfile.followStatus === 'requested'">
                                    <span>Requested</span>
                                </template>
                                <template x-if="activeProfile.followStatus === 'followed'">
                                    <span>Followed</span>
                                </template>
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
                                            'followStatus' => \App\Models\FollowRelation::where('user_id', auth()->id())->where('follow_user_id', $access->user->id)->exists() 
                                                ? 'followed' 
                                                : (\App\Models\FollowRequest::where('user_id', $access->user->id)->where('requester_id', auth()->id())->exists() ? 'requested' : 'none')
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

    <div x-show="showShareModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
        <div @click="showShareModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl" x-transition>
            <h3 class="text-2xl font-bold mb-6">Share Project</h3>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-2xl border flex items-center justify-between">
                    <span class="text-sm text-gray-500 truncate mr-4">{{ url()->current() }}</span>
                    <button @click="copyLink()" class="px-4 py-2 bg-black text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition">
                        <span x-text="copyText">Copy Link</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-8">
                    <a href="{{ route('projects.export', $project) }}" target="_blank" class="flex flex-col items-center gap-3 p-6 rounded-[24px] border border-gray-100 hover:border-black hover:bg-gray-50 transition">
                        <i class="fa-solid fa-file-pdf text-3xl text-red-500"></i>
                        <span class="text-sm font-bold">Print PDF / PNG</span>
                    </a>
                    <button @click="shareWhatsApp()" class="flex flex-col items-center gap-3 p-6 rounded-[24px] border border-gray-100 hover:border-emerald-500 hover:bg-emerald-50 transition">
                        <i class="fa-brands fa-whatsapp text-3xl text-emerald-500"></i>
                        <span class="text-sm font-bold">WhatsApp</span>
                    </button>
                </div>
            </div>
            
            <button @click="showShareModal = false" class="mt-8 w-full py-4 text-gray-400 font-bold hover:text-black transition">Close</button>
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
        showShareModal: false,
        copyText: 'Copy Link',
        isProcessing: false,

        // Data Interaksi
        isLiked: {{ $project->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }},
        isSaved: {{ $project->saveds()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }},
        likeCount: {{ $project->detail->like_count ?? 0 }},

        activeProfile: {
            id: {{ $project->owner->id }},
            name: '{{ $project->owner->name }}',
            email: '{{ $project->owner->email }}',
            photo: '{{ asset('storage/' . ($project->owner->profile->photo_profile ?? 'default.jpg')) }}',
            followers: {{ $project->owner->profile->followers ?? 0 }},
            following: {{ $project->owner->profile->following ?? 0 }},
            followStatus: '{{ \App\Models\FollowRelation::where('user_id', auth()->id())->where('follow_user_id', $project->owner->id)->exists() ? 'followed' : (\App\Models\FollowRequest::where('user_id', $project->owner->id)->where('requester_id', auth()->id())->exists() ? 'requested' : 'none') }}'
        },

        toggleLike() {
            fetch("{{ route('projects.like', $project) }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                this.isLiked = data.status;
                this.likeCount = data.like_count;
            });
        },

        toggleSave() {
            fetch("{{ route('projects.save', $project) }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                this.isSaved = data.status;
                if(this.isSaved) alert('Project saved to bookmarks');
            });
        },

        copyLink() {
            navigator.clipboard.writeText(window.location.href);
            this.copyText = 'Copied!';
            setTimeout(() => this.copyText = 'Copy Link', 2000);
        },

        shareWhatsApp() {
            window.open(`https://wa.me/?text=Check out this awesome project: ${window.location.href}`, '_blank');
        },
        
        nextImage() {
            this.activeImage = (this.activeImage + 1) % this.totalImages;
        },
        
        prevImage() {
            this.activeImage = (this.activeImage - 1 + this.totalImages) % this.totalImages;
        },

        async handleFollow() {
            if(this.isProcessing) return;
            this.isProcessing = true;

            try {
                // Gunakan URL dinamis berdasarkan activeProfile.id
                const response = await fetch(`/follow/${this.activeProfile.id}/toggle`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                // Update state Alpine
                this.activeProfile.followStatus = data.status;
                this.activeProfile.followers = data.followers;

            } catch (error) {
                console.error("Follow error:", error);
            } finally {
                this.isProcessing = false;
            }
        },

        switchProfile(data) {
            this.activeProfile = data;
        },

        confirmDelete() {
        if(confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
            const form = document.getElementById('delete-project-form');
            if (form) {
                form.submit();
            } else {
                console.error('Delete form not found');
            }
        }
    },

        alertVectra() {
            alert('Crevolio Vectra 1.0 (Beta) will be available in February 2026');
        }
    }
}
</script>
@endsection