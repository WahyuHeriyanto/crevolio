@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900">Notifications</h1>
            <p class="text-gray-500">Stay updated with your projects and collaborations.</p>
        </div>
        
        {{-- Menggunakan query langsung agar variabel $notifications tidak berubah jadi Collection --}}
        @if(\App\Models\UserNotification::where('user_id', auth()->id())->where('is_read', 0)->exists())
            <form action="{{ route('notifications.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <div class="space-y-3">
        @forelse($notifications as $notif)
            <a href="{{ route('notifications.read', $notif->id) }}" 
               class="block p-5 rounded-[24px] border transition-all duration-200 {{ $notif->is_read ? 'bg-white border-gray-100 opacity-75' : 'bg-white border-indigo-200 shadow-md shadow-indigo-50 ring-1 ring-indigo-50' }}">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $notif->type == 'project_join' ? 'bg-amber-100 text-amber-600' : 'bg-indigo-100 text-indigo-600' }}">
                            <i class="fa-solid {{ $notif->type == 'project_join' ? 'fa-user-plus' : 'fa-bell' }} text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-bold text-gray-900 {{ $notif->is_read ? '' : 'text-indigo-900' }}">
                                {{ $notif->title }}
                            </h4>
                            <span class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $notif->message }}</p>
                    </div>
                    @if(!$notif->is_read)
                        <div class="flex-shrink-0 self-center">
                            <div class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="text-center py-20 bg-gray-50 rounded-[32px] border-2 border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-bell-slash text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">All caught up!</h3>
                <p class="text-gray-500">No new notifications for you right now.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
</div>
@endsection