@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 mb-20">
    
    {{-- HEADER SECTION --}}
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-5">
            <!-- <a href="{{ route('dashboard') }}" class="w-12 h-12 flex items-center justify-center bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-black hover:shadow-md transition duration-300">
                <i class="fa-solid fa-arrow-left"></i>
            </a> -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
                <p class="text-gray-500 mt-1">You have {{ $users->total() }} pending follow requests.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-10">
        {{-- MAIN LIST --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
                @forelse ($users as $request)
                    @php 
                        // Mempermudah akses data requester (user yang minta follow)
                        $u = $request->requester; 
                    @endphp
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-8 {{ !$loop->last ? 'border-b border-gray-50' : '' }} hover:bg-gray-50/50 transition duration-300 gap-6">
                        <div class="flex items-center gap-6">
                            {{-- Photo Profile --}}
                            <div class="relative">
                                <img 
                                    src="{{ $u->profile->photo_profile ? asset('storage/' . $u->profile->photo_profile) : asset('images/default-avatar.png') }}" 
                                    alt="{{ $u->name }}"
                                    class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-sm"
                                >
                            </div>
                            
                            {{-- Info --}}
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 hover:text-indigo-600 transition">
                                    {{ $u->name }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest">
                                        {{ $u->profile?->careerPosition?->name ?? 'Creative Member' }}
                                    </span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-xs text-gray-400">Requested {{ $request->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-3">
                            {{-- Tombol View --}}
                            <div class="flex items-center">
                            <a 
                                href="{{ route('profile.show', $u->username) }}" 
                                class="px-6 py-3 rounded-2xl bg-gray-900 text-white text-xs font-bold hover:bg-black hover:shadow-lg transform hover:-translate-y-0.5 transition duration-300"
                            >
                                View
                            </a>
                        </div>
                            {{-- Tombol Accept --}}
                            <form action="{{ route('profile.accept-request', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 rounded-2xl bg-black text-white text-xs font-bold hover:bg-indigo-600 hover:shadow-lg transform hover:-translate-y-0.5 transition duration-300">
                                    Accept
                                </button>
                            </form>

                            {{-- Tombol Decline --}}
                            <form action="{{ route('profile.decline-request', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 rounded-2xl bg-white border border-gray-200 text-gray-500 text-xs font-bold hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition duration-300">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-user-clock text-2xl text-gray-200"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">No Pending Requests</h3>
                        <p class="text-gray-400 mt-2 max-w-xs mx-auto">When someone requests to follow your private profile, they will appear here.</p>
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
        
        {{-- Privacy Alert Box (Hanya muncul jika status PRIVATE) --}}
        @if(auth()->user()->profile->status === 'private')
            <div class="p-8 bg-amber-50 rounded-[40px] border border-amber-100">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mb-6 text-amber-600">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h4 class="font-bold text-amber-900 text-lg mb-2">Private Mode Active</h4>
                <p class="text-sm text-amber-700 leading-relaxed">
                    Because your profile is <strong>Private</strong>, you need to manually approve people before they can follow you.
                </p>
                <a href="{{ route('profile.edit') }}" class="inline-block mt-6 text-xs font-bold text-amber-900 underline decoration-2 underline-offset-4 uppercase tracking-wider">
                    Change to Public
                </a>
            </div>
        @else
            {{-- Tampilan jika status PUBLIC --}}
            <div class="p-8 bg-indigo-50 rounded-[40px] border border-indigo-100">
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6 text-indigo-600">
                    <i class="fa-solid fa-earth-americas"></i>
                </div>
                <h4 class="font-bold text-indigo-900 text-lg mb-2">Public Profile</h4>
                <p class="text-sm text-indigo-700 leading-relaxed">
                    Your profile is currently <strong>Public</strong>. Requests here might be from when you were in Private mode.
                </p>
                <a href="{{ route('profile.edit') }}" class="inline-block mt-6 text-xs font-bold text-indigo-900 underline decoration-2 underline-offset-4 uppercase tracking-wider">
                    Switch to Private
                </a>
            </div>
        @endif

        {{-- Quick Stats --}}
        <div class="p-8 bg-white rounded-[40px] border border-gray-100 shadow-sm text-center">
            <h4 class="font-bold text-gray-900 mb-6">Your Network</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($user->profile->followers) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Followers</p>
                </div>
                <div class="border-l border-gray-50">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($user->profile->following) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Following</p>
                </div>
            </div>
        </div>
    </div>
</aside>
    </div>
</div>
@endsection