@extends('vectra.layouts.app')

@section('content')
<div class="absolute inset-0 flex flex-col min-h-0 bg-[#F8F9FB] p-6">
    
    <div class="flex h-full w-full gap-6 min-h-0">

        <aside class="w-72 bg-white rounded-2xl border border-gray-200 flex flex-col shrink-0 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b shrink-0 bg-white">
                <h2 class="font-bold text-sm text-gray-800 uppercase tracking-wider">Collaborators</h2>
            </div>
            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                @foreach ($participants as $access)
                    <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                        <img src="{{ $access->user->profile?->photo_profile ? asset('storage/'.$access->user->profile->photo_profile) : asset('assets/images/photo-profile-default.png') }}"
                             class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-100" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $access->user->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $access->project_role ?? 'Member' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        <section class="flex-1 bg-white rounded-2xl border border-gray-200 flex flex-col min-h-0 shadow-sm overflow-hidden">
            
            <div class="px-6 py-4 border-b shrink-0 bg-white flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900">Room : {{ $project->name }}</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $participants->count() }} members online</p>
                </div>
            </div>

            <div id="chat-body" class="flex-1 overflow-y-auto bg-gray-50 custom-scrollbar flex flex-col min-h-0">
                @if ($messages->isEmpty())
                    <div class="flex-1 flex flex-col items-center justify-center text-gray-300 gap-3">
                        <i class="fa-solid fa-comments text-4xl"></i>
                        <p class="text-sm font-bold uppercase tracking-widest">Start the conversation</p>
                    </div>
                @else
                    <div class="p-6 space-y-6">
                        @foreach ($messages as $message)
                            @php $isMine = $message->sender_id === auth()->id(); @endphp
                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[75%] lg:max-w-[60%]">
                                    @if (!$isMine)
                                        <p class="mb-1 text-[10px] font-bold text-gray-400 uppercase ml-1">{{ $message->sender->name }}</p>
                                    @endif
                                    <div class="rounded-2xl px-4 py-3 text-sm shadow-sm leading-relaxed {{ $isMine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-gray-100 text-gray-800 rounded-bl-none' }}">
                                        <p class="break-words">{{ $message->content['text'] }}</p>
                                        <span class="block mt-1 text-[9px] font-bold opacity-60 text-right">{{ $message->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-4 bg-white border-t shrink-0">
                <form action="{{ route('chat.send', $conversation) }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="text" name="message" autocomplete="off" placeholder="Write your message..." 
                           class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all shadow-inner" />
                    <button
                        class="bg-indigo-600 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-indigo-700 transition"
                    >
                        Send
                    </button>
                </form>
            </div>

        </section>
    </div>
</div>

@push('scripts')
<script>
    const chatBody = document.getElementById('chat-body');
    if (chatBody) {
        chatBody.scrollTop = chatBody.scrollHeight;
    }
</script>
@endpush
@endsection