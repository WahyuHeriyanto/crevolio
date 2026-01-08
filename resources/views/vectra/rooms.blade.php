@extends('vectra.layouts.app')

@section('content')

<div class="flex h-[calc(100vh-4rem)] gap-6">

    {{-- LEFT : PARTICIPANTS --}}
    <aside class="w-72 bg-white rounded-2xl border flex flex-col min-h-0">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b">
            <h2 class="font-semibold text-sm text-gray-700">
                Collaborators
            </h2>
        </div>

        {{-- LIST --}}
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-2">

            @foreach ($participants as $access)
                @php
                    $user = $access->user;
                    $role = $access->project_role ?? 'Member';
                @endphp

                <div class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-100 cursor-pointer transition">

                    <img
                        src="{{ $user->profile?->photo_profile
                            ? asset('storage/'.$user->profile->photo_profile)
                            : asset('assets/images/photo-profile-default.png') }}"
                        class="w-10 h-10 rounded-full object-cover"
                    />

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">
                            {{ $user->name }}
                        </p>
                        <p class="text-xs text-gray-400 truncate">
                            {{ $role }}
                        </p>
                    </div>

                </div>
            @endforeach

        </div>

    </aside>

    {{-- RIGHT : CHAT ROOM --}}
    <section class="flex-1 bg-white rounded-2xl border flex flex-col min-h-0">

        {{-- CHAT HEADER --}}
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-gray-800">
                    Room : {{ $project->name }}
                </h2>
                <p class="text-xs text-gray-400">
                    {{ $participants->count() }} members
                </p>
            </div>
        </div>

        {{-- MESSAGES --}}
        <div id="chat-body" class="flex-1 overflow-y-auto bg-gray-50">

            @if ($messages->isEmpty())
                <div class="h-full flex items-center justify-center text-gray-400 text-sm">
                    No messages yet. Start the conversation 🚀
                </div>
            @else
                <div class="px-6 py-10 space-y-4">
        @foreach ($messages as $message)
            @php
                $isMine = $message->sender_id === auth()->id();
                $senderName = $message->sender->name;
            @endphp

            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[70%]">

                    {{-- SENDER NAME --}}
                    @if (! $isMine)
                        <p class="mb-1 text-xs text-gray-500">
                            {{ $senderName }}
                        </p>
                    @endif

                    <div class="rounded-2xl px-4 py-2 text-sm
                        {{ $isMine
                            ? 'bg-indigo-600 text-white rounded-br-md'
                            : 'bg-white border rounded-bl-md' }}">

                        <p class="leading-relaxed">
                            {{ $message->content['text'] }}
                        </p>

                        <span class="block mt-1 text-[10px] opacity-70 text-right">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>

                </div>
            </div>
        @endforeach

        </div>
    @endif

</div>
        {{-- COMPOSER --}}
        <form
            action="{{ route('chat.send', $conversation) }}"
            method="POST"
            class="px-6 py-4 border-t flex items-center gap-3"
        >
            @csrf

            <input
                type="text"
                name="message"
                placeholder="Type a message..."
                class="flex-1 rounded-full border px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />

            <button
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-full text-sm font-medium hover:bg-indigo-700 transition"
            >
                Send
            </button>
        </form>

    </section>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chat = document.getElementById('chat-body');
        if (chat) {
            chat.scrollTop = chat.scrollHeight;
        }
    });
</script>
@endpush

@endsection
