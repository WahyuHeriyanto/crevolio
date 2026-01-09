@extends('vectra.layouts.app')

@section('content')
<div class="absolute inset-0 flex flex-col min-h-0 bg-[#F8F9FB] p-6">
    
    {{-- Alert untuk Poin 3 --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm font-bold rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex h-full w-full gap-6 min-h-0">
        {{-- SIDEBAR COLLABORATORS --}}
        <aside class="w-72 bg-white rounded-2xl border border-gray-200 flex flex-col shrink-0 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b shrink-0 bg-white">
                <h2 class="font-bold text-sm text-gray-800 uppercase tracking-wider">Collaborators</h2>
            </div>
            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                @foreach ($participants as $participant)
                    @php $user = $participant->user; @endphp
                    <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">
                        <img src="{{ $user->profile?->photo_profile ? asset('storage/'.$user->profile->photo_profile) : asset('assets/images/photo-profile-default.png') }}"
                             class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-100" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $user->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Member</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </aside>

        {{-- CHAT AREA --}}
        <section class="flex-1 bg-white rounded-2xl border border-gray-200 flex flex-col min-h-0 shadow-sm overflow-hidden relative">
            
            <div class="px-6 py-4 border-b shrink-0 bg-white flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-gray-900">Room : {{ $project->name ?? 'General' }}</h2>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $participants->count() }} members</p>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 hover:bg-gray-100 rounded-full transition">
                        <i class="fa-solid fa-ellipsis-vertical text-gray-400"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 py-2" x-cloak>
                        <form action="{{ route('chat.clear', $conversation) }}" method="POST" onsubmit="return confirm('Clean all messages?')">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold transition">
                                <i class="fa-solid fa-trash-can mr-2"></i> Clean room chat
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MESSAGES (Poin 1: Menghapus flex-col-reverse agar urutan natural atas ke bawah) --}}
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
                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-3 group">
                                @if(!$isMine)
                                    <img src="{{ $message->sender->profile?->photo_profile ? asset('storage/'.$message->sender->profile->photo_profile) : asset('assets/images/photo-profile-default.png') }}"
                                         class="w-8 h-8 rounded-full object-cover shrink-0 shadow-sm border border-gray-200" title="{{ $message->sender->name }}" />
                                @endif

                                <div class="max-w-[75%] lg:max-w-[60%] relative message-wrapper" 
                                     data-id="{{ $message->id }}" 
                                     data-text="{{ $message->content['text'] }}"
                                     data-mine="{{ $isMine ? 'true' : 'false' }}">
                                    
                                    @if (!$isMine)
                                        <p class="mb-1 text-[10px] font-bold text-gray-400 uppercase ml-1">{{ $message->sender->name }}</p>
                                    @endif

                                    <div class="rounded-2xl px-4 py-3 text-sm shadow-sm leading-relaxed transition-all {{ $isMine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-gray-100 text-gray-800 rounded-bl-none' }}">
                                        <p class="break-words font-medium message-content-text">{{ $message->content['text'] }}</p>
                                        
                                        <div class="flex items-center justify-end gap-2 mt-1 opacity-60">
                                            @if(isset($message->content['is_edited']))
                                                <span class="text-[8px] italic font-bold">edited</span>
                                            @endif
                                            {{-- Poin 4: Deteksi Zona Waktu lewat class 'local-time' --}}
                                            <span class="local-time block text-[9px] font-bold text-right" data-utc="{{ $message->created_at->toIso8601String() }}">
                                                {{ $message->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- INPUT BOX --}}
            <div class="p-4 bg-white border-t shrink-0">
                <form action="{{ route('chat.send', $conversation) }}" method="POST" id="main-chat-form" class="flex items-center gap-3">
                    @csrf
                    <input type="text" name="message" id="chat-input-field" autocomplete="off" placeholder="Write your message..." 
                           class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-5 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition-all shadow-inner" required />
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-indigo-700 transition">
                        SEND
                    </button>
                </form>
            </div>
        </section>
    </div>

    {{-- CONTEXT MENU (Klik Kanan) --}}
    <div id="context-menu" class="fixed hidden bg-white border border-gray-100 rounded-xl shadow-2xl z-[100] w-48 py-2 overflow-hidden">
        <button id="menu-edit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3 transition font-bold">
            <i class="fa-solid fa-pen text-gray-400"></i> Edit message
        </button>
        <form id="menu-unsend-form" method="POST" class="w-full">
            @csrf @method('DELETE')
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-3 font-bold transition">
                <i class="fa-solid fa-rotate-left"></i> Unsend message
            </button>
        </form>
    </div>

    {{-- MODAL EDIT (Poin 2) --}}
    <div id="edit-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[110] p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="p-6">
                <h3 class="font-black text-gray-900 mb-4 uppercase tracking-widest text-sm">Edit Message</h3>
                <form id="edit-form" method="POST">
                    @csrf @method('PUT')
                    <textarea name="message" id="edit-textarea" rows="4" class="w-full rounded-xl border border-gray-200 p-4 text-sm focus:ring-2 focus:ring-indigo-600 outline-none resize-none mb-4"></textarea>
                    <div class="flex gap-3">
                        <button type="button" id="close-edit" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 text-sm font-bold text-gray-500 hover:bg-gray-50 transition">CANCEL</button>
                        <button type="submit" class="flex-1 px-4 py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition">SAVE CHANGES</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatBody = document.getElementById('chat-body');
        const contextMenu = document.getElementById('context-menu');
        const unsendForm = document.getElementById('menu-unsend-form');
        const editModal = document.getElementById('edit-modal');
        const editForm = document.getElementById('edit-form');
        const editTextarea = document.getElementById('edit-textarea');
        const closeEdit = document.getElementById('close-edit');

        // Poin 1: Scroll ke bawah
        chatBody.scrollTop = chatBody.scrollHeight;

        // Poin 4: Konversi Waktu ke Lokal User
        document.querySelectorAll('.local-time').forEach(el => {
            const utcDate = el.getAttribute('data-utc');
            if (utcDate) {
                const date = new Date(utcDate);
                el.innerText = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            }
        });

        // Klik Kanan Handler
        document.querySelectorAll('.message-wrapper').forEach(msg => {
            msg.addEventListener('contextmenu', (e) => {
                const isMine = msg.getAttribute('data-mine') === 'true';
                if (isMine) {
                    e.preventDefault();
                    const msgId = msg.getAttribute('data-id');
                    const currentText = msg.getAttribute('data-text');

                    unsendForm.action = `/chat/message/${msgId}`;
                    
                    // Setup Edit Button
                    document.getElementById('menu-edit').onclick = () => {
                        editForm.action = `/chat/message/${msgId}`;
                        editTextarea.value = currentText;
                        editModal.classList.replace('hidden', 'flex');
                        contextMenu.classList.add('hidden');
                    };

                    contextMenu.classList.remove('hidden');
                    contextMenu.style.top = `${e.clientY}px`;
                    contextMenu.style.left = `${e.clientX}px`;
                }
            });
        });

        closeEdit.onclick = () => editModal.classList.replace('flex', 'hidden');
        window.onclick = (e) => {
            if (e.target === editModal) editModal.classList.replace('flex', 'hidden');
            contextMenu.classList.add('hidden');
        };
    });
</script>
@endpush
@endsection