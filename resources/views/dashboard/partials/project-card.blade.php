<div x-data="{ 
        liked: {{ $project->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}, 
        saved: {{ $project->saveds()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }},
        count: {{ $project->detail->like_count ?? 0 }},
        
        toggleLike() {
            fetch('{{ route('projects.like', $project->id) }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                } 
            })
            .then(res => res.json())
            .then(data => {
                this.liked = data.status;
                this.count = data.like_count;
            });
        },
        
        toggleSave() {
            fetch('{{ route('projects.save', $project->id) }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                } 
            })
            .then(res => res.json())
            .then(data => {
                this.saved = data.status;
                if(this.saved) alert('Project saved to bookmarks');
            });
        }
    }" 
    class="relative bg-white rounded-[32px] p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 group cursor-pointer"
    @click="window.location.href='{{ route('projects.show', $project->id) }}'"
>
    {{-- Header: Profile & Status --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3" @click.stop>
            <img src="{{ $project->owner->profile->photo_profile ? asset('storage/' . $project->owner->profile->photo_profile) : asset('images/default-avatar.png') }}" 
                 class="w-10 h-10 rounded-full object-cover border-2 border-indigo-50">
            <div>
                <a href="{{ route('profile.show', $project->owner->username) }}" class="font-bold text-gray-900 hover:text-indigo-600 transition text-sm">{{ $project->owner->name }}</a>
                <div class="text-gray-400 text-[10px] font-medium uppercase tracking-tight">{{ $project->created_at->diffForHumans() }}</div>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            @php 
                $statusName = $project->detail->status->name ?? 'Unknown';
                $statusColor = $statusName == 'Open' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600'; 
            @endphp
            <span class="{{ $statusColor }} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                {{ $statusName }}
            </span>

            <button @click.stop="toggleSave" class="p-2 rounded-full transition-colors" :class="saved ? 'text-indigo-600 bg-indigo-50' : 'text-gray-300 hover:bg-gray-50'">
                <i class="fa-bookmark transition-all" :class="saved ? 'fa-solid' : 'fa-regular'"></i>
            </button>
        </div>
    </div>

    {{-- Project Image & Tools --}}
    @php $cover = $project->medias->first(); @endphp
    <div class="relative group mb-4 overflow-hidden rounded-[20px] aspect-[16/7] bg-gray-50">
        <img src="{{ $cover ? asset('storage/' . $cover->url) : 'https://ui-avatars.com/api/?name='.urlencode($project->name).'&size=512' }}" 
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        
        <div class="absolute bottom-3 left-3 flex flex-wrap gap-2">
            @foreach($project->detail->tools->take(3) as $projectTool)
                <span class="bg-black/50 backdrop-blur-md text-white text-[9px] px-2.5 py-1 rounded-lg border border-white/20">
                    {{ $projectTool->tool->name }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Title & Field --}}
    <div class="mb-4">
        <div class="flex justify-between items-start mb-1">
            <h3 class="font-black text-lg text-gray-900 tracking-tight leading-tight group-hover:text-indigo-600 transition">
                {{ $project->name }}
            </h3>
            <span class="text-indigo-600 font-bold text-[9px] bg-indigo-50 px-2 py-0.5 rounded-md uppercase">
                {{ $project->detail->field->name ?? 'General' }}
            </span>
        </div>
        <p class="text-gray-500 text-xs leading-relaxed line-clamp-3">
            {{ $project->detail->description }}
        </p>
    </div>

    {{-- Timeline (Desain Lama yang Kamu Suka) --}}
    <div class="grid grid-cols-2 gap-3 mb-5 p-2.5 bg-gray-50 rounded-2xl border border-gray-100/50">
        <div class="flex flex-col border-r border-gray-200">
            <span class="text-[8px] uppercase font-bold text-gray-400 tracking-widest">Start Date</span>
            <span class="text-[11px] font-bold text-gray-700">{{ \Carbon\Carbon::parse($project->detail->start_date)->format('d M Y') }}</span>
        </div>
        <div class="flex flex-col pl-2">
            <span class="text-[8px] uppercase font-bold text-gray-400 tracking-widest">End Date</span>
            <span class="text-[11px] font-bold text-gray-700">{{ \Carbon\Carbon::parse($project->detail->end_date)->format('d M Y') }}</span>
        </div>
    </div>

    {{-- Footer: Like & Collab --}}
    <div class="flex items-center justify-between border-t border-gray-50 pt-4">
        <div class="flex items-center gap-4" @click.stop>
            {{-- Like --}}
            <button @click="toggleLike" class="flex items-center gap-2 group/like">
                <div class="w-9 h-9 rounded-full flex items-center justify-center transition-colors" :class="liked ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-50 text-gray-400 group-hover/like:bg-indigo-50 group-hover/like:text-indigo-600'">
                    <i class="fa-solid fa-thumbs-up text-xs transition-transform active:scale-125"></i>
                </div>
                <span class="text-xs font-black transition-colors" :class="liked ? 'text-indigo-600' : 'text-gray-600'" x-text="count"></span>
            </button>

            {{-- Collaborators --}}
            <div class="flex -space-x-3 overflow-hidden">
                @foreach($project->detail->collaborators->take(4) as $collab)
                    <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white object-cover" 
                         src="{{ asset('storage/' . ($collab->user->profile->photo_profile ?? 'default.jpg')) }}" 
                         title="{{ $collab->user->name }}">
                @endforeach
                @if($project->detail->collaborators->count() > 4)
                    <div class="flex items-center justify-center h-7 w-7 rounded-full bg-gray-900 text-[9px] font-bold text-white ring-2 ring-white">
                        +{{ $project->detail->collaborators->count() - 4 }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>