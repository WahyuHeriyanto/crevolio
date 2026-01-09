@php
    $isDashboard = request()->routeIs('vectra.dashboard');
    $isRooms = request()->routeIs('vectra.rooms*');
@endphp
<div class="w-full flex flex-col items-center py-6 gap-8 overflow-y-auto h-full">
    <a href="{{ route('vectra.dashboard') }}" class="group flex flex-col items-center gap-2">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center transition {{ $isDashboard ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-400' }}">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <span class="text-[10px] font-bold uppercase {{ $isDashboard ? 'text-indigo-600' : 'text-gray-400' }}">Projects</span>
    </a>
    @unless ($isDashboard)
        <a href="#" class="group flex flex-col items-center gap-2 pointer-events-none opacity-50">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $isRooms ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-50 text-gray-400' }}">
                <i class="fa-solid fa-comments"></i>
            </div>
            <span class="text-[10px] font-bold uppercase {{ $isRooms ? 'text-indigo-600' : 'text-gray-400' }}">Rooms</span>
        </a>
    @endunless
</div>  