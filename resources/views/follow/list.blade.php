@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 mb-20">
    
    {{-- HEADER SECTION --}}
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-5">
            <!-- <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-black hover:shadow-md transition duration-300">
                <i class="fa-solid fa-arrow-left"></i>
            </a> -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
                <p class="text-gray-500 mt-1">Discovering {{ $users->total() }} talented creators in this list.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-10">
        {{-- MAIN LIST --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
                @forelse ($users as $u)
                    <div class="flex items-center justify-between p-8 {{ !$loop->last ? 'border-b border-gray-50' : '' }} hover:bg-gray-50/50 transition duration-300">
                        <div class="flex items-center gap-6">
                            {{-- Photo with Status Ring --}}
                            <div class="relative">
                                <img 
                                    src="{{ $u->profile->photo_profile ? asset('storage/' . $u->profile->photo_profile) : asset('images/default-avatar.png') }}" 
                                    alt="{{ $u->name }}"
                                    class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-sm"
                                >
                                <div class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                            </div>
                            
                            {{-- Info --}}
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition cursor-pointer">
                                    {{ $u->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest">
                                        {{ $u->profile?->careerPosition?->name ?? 'Creative Member' }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <div class="flex gap-2">
                                        @foreach($u->profile?->expertises->take(2) ?? [] as $exp)
                                            <span class="text-xs text-gray-400">#{{ $exp->expertise->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="flex items-center">
                            <a 
                                href="{{ route('profile.show', $u->username) }}" 
                                class="px-6 py-3 rounded-2xl bg-gray-900 text-white text-xs font-bold hover:bg-black hover:shadow-lg transform hover:-translate-y-0.5 transition duration-300"
                            >
                                View Profile
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-users text-2xl text-gray-200"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">No {{ $type }} yet</h3>
                        <p class="text-gray-400 mt-2 max-w-xs mx-auto">This list is currently empty. Start exploring to find amazing creators.</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-8 px-8 py-3 bg-black text-white rounded-2xl text-sm font-bold hover:shadow-xl transition">
                            Explore Community
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $users->links() }}
            </div>
        </div>

        {{-- SIDEBAR INFO --}}
        <aside class="hidden lg:block lg:col-span-4">
            <div class="sticky top-24 space-y-6">
                {{-- User Mini Profile --}}
                <div class="p-8 bg-white rounded-[40px] border border-gray-100 shadow-sm text-center">
                    <img 
                        src="{{ $user->profile->photo_profile ? asset('storage/' . $user->profile->photo_profile) : asset('images/default-avatar.png') }}" 
                        class="w-20 h-20 rounded-full mx-auto mb-4 border-4 border-indigo-50"
                    >
                    <h4 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h4>
                    <p class="text-sm text-gray-500 mb-6">{{ '@' . $user->username }}</p>
                    
                    <div class="grid grid-cols-2 gap-4 py-4 border-t border-gray-50">
                        <div>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($user->profile->followers) }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Followers</p>
                        </div>
                        <div class="border-l border-gray-50">
                            <p class="text-xl font-bold text-gray-900">{{ number_format($user->profile->following) }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Following</p>
                        </div>
                    </div>
                </div>

                {{-- Community Guide --}}
                <div class="p-8 bg-indigo-900 rounded-[40px] text-white shadow-xl shadow-indigo-100">
                    <h4 class="font-bold mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-indigo-400"></i>
                        Networking Tip
                    </h4>
                    <p class="text-indigo-100 text-sm leading-relaxed opacity-90">
                        Building a network of {{ $type }} helps you stay updated with new trends and opens opportunities for future project collaborations.
                    </p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection