<div class="bg-white rounded-2xl p-5 shadow-sm sticky top-24">
    @php
            $userPhoto = auth()->user()->profile->photo_profile 
                        ? asset('storage/' . auth()->user()->profile->photo_profile) 
                        : asset('images/default-avatar.png');
    @endphp
    <div class="flex items-center gap-4 mb-4">
        <img
            src="{{ $userPhoto }}"
            class="w-14 h-14 rounded-full"
        />
        <div>
            <div class="font-semibold">
                {{ Auth::user()->name ?? 'Your Name' }}
            </div>
            <div class="text-sm text-gray-500">
                Product Builder
            </div>
        </div>
    </div>

    <div class="text-sm text-gray-600 mb-4">
        Building digital products & collaborating with passionate people.
    </div>

    <div class="space-y-2 text-sm">
        <a href="#" class="block text-gray-700 hover:text-black">
            Saved Projects
        </a>
        <a href="#" class="block text-gray-700 hover:text-black">
            My Collaborations
        </a>
        <a href="#" class="block text-gray-700 hover:text-black">
            Drafts
        </a>
    </div>
</div>
