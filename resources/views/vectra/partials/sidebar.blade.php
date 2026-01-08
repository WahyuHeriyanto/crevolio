@php
    $active = request()->routeIs('vectra.dashboard') ? 'dashboard' : 'rooms';
@endphp

{{-- Tambahkan 'flex-1' di div pembungkus utama --}}
<div class="w-full flex flex-col items-center py-6 gap-8 flex-1 h-screen">

    <a href="{{ route('vectra.dashboard') }}" class="group flex flex-col items-center gap-2">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-200
            {{ $active === 'dashboard' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100' }}">
            <i class="fa-solid fa-layer-group text-lg"></i>
        </div>
        <span class="text-[10px] font-bold uppercase tracking-wider {{ $active === 'dashboard' ? 'text-indigo-600' : 'text-gray-400' }}">
            Projects
        </span>
    </a>

    <a href="{{ route('vectra.rooms') }}" class="group flex flex-col items-center gap-2">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-200
            {{ $active === 'rooms' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-400 group-hover:bg-gray-100' }}">
            <i class="fa-solid fa-comments text-lg"></i>
        </div>
        <span class="text-[10px] font-bold uppercase tracking-wider {{ $active === 'rooms' ? 'text-indigo-600' : 'text-gray-400' }}">
            Rooms
        </span>
    </a>

</div>