<div class="space-y-6">

    {{-- COVER --}}
    <div class="relative h-48 rounded-2xl overflow-hidden bg-gray-200">
        <img
            src="https://picsum.photos/1200/300"
            class="w-full h-full object-cover"
        />
    </div>

    {{-- MAIN HEADER --}}
    <div class="relative -mt-16 flex justify-between items-end gap-6 px-4">

        {{-- LEFT : AVATAR + INFO --}}
        <div class="flex items-end gap-6">

            {{-- AVATAR --}}
            <img
                src="https://i.pravatar.cc/150"
                class="
                    w-36 h-36
                    rounded-full
                    border-4 border-white
                    shadow-lg
                    bg-white
                    relative
                    -top-10
                "
            />

            {{-- INFO CARD --}}
            <div class="bg-white/90 backdrop-blur rounded-xl px-6 py-4 shadow-sm max-w-xl">
                <h1 class="text-2xl font-semibold">
                    {{ $user->name }}
                </h1>

                <p class="text-gray-600 mt-1">
                    Building digital products & collaborating with passionate people.
                </p>

                {{-- TAGS --}}
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100">Laravel</span>
                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100">UI/UX</span>
                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100">Product</span>
                </div>

                {{-- SOCIAL --}}
                <div class="flex gap-4 mt-4 text-gray-500">
                    <a href="mailto:test@gmail.com" target="_blank" class="hover:text-black">✉️</a>
                    <a href="https://instagram.com" target="_blank" class="hover:text-black">📸</a>
                    <a href="https://linkedin.com" target="_blank" class="hover:text-black">💼</a>
                </div>
            </div>
        </div>

        {{-- RIGHT : ACTIONS --}}
        <div class="flex items-center gap-3 mb-4 mr-6">

            {{-- FOLLOW (only other user) --}}
            @auth
                @if(auth()->id() !== $user->id)
                    <button
                        class="px-4 py-2 text-sm rounded-full border hover:bg-gray-100 transition"
                    >
                        Follow
                    </button>
                @endif
            @endauth

            {{-- OWNER ACTION (1 BUTTON BASED ON TAB) --}}
            @auth
                @if(auth()->id() === $user->id)

                    {{-- Create Project --}}
                    <a
                        x-show="tab === 'projects'"
                        href="#"
                        class="px-5 py-2 text-sm rounded-full bg-black text-white hover:bg-gray-800 transition"
                    >
                        + Create Project
                    </a>

                    {{-- Add Portfolio --}}
                    <a
                        x-show="tab === 'portfolios'"
                        href="#"
                        class="px-5 py-2 text-sm rounded-full bg-black text-white hover:bg-gray-800 transition"
                    >
                        + Add Portfolio
                    </a>

                @endif
            @endauth

        </div>

    </div>
</div>
