@php
    $startDate = $portfolio->start_date ? \Carbon\Carbon::parse($portfolio->start_date) : null;
    $endDate = $portfolio->end_date ? \Carbon\Carbon::parse($portfolio->end_date) : null;
    $isOngoing = !$endDate;
    
    $duration = '';
    if($startDate) {
        $diff = $startDate->diffInMonths($endDate ?? now());
        $years = floor($diff / 12);
        $months = $diff % 12;
        $duration = ($years > 0 ? $years . ' thn ' : '') . ($months > 0 ? $months . ' bln' : '');
    }
@endphp

<div class="relative pl-12 md:pl-24 group">
    {{-- Titik Node Timeline (Dibuat lebih besar & bercahaya) --}}
    <div class="absolute left-3.5 md:left-7.5 top-2 w-4 h-4 rounded-full bg-white border-[3px] border-indigo-600 z-10 shadow-[0_0_0_4px_rgba(79,70,229,0.1)] group-hover:shadow-[0_0_0_6px_rgba(79,70,229,0.2)] transition-all"></div>

    <div class="flex flex-col lg:flex-row gap-8 bg-white p-7 rounded-[2rem] border border-gray-100 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100/40 transition-all duration-500">
        
        {{-- Gambar Utama (Rasio diperbaiki jadi 16:9 agar lebih lebar/wah) --}}
        @if($portfolio->medias->count() > 0)
            <div class="w-full lg:w-64 h-40 flex-shrink-0 rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
                <img src="{{ asset('storage/' . $portfolio->medias->first()->url) }}" 
                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
            </div>
        @else
            {{-- Placeholder jika tidak ada gambar agar layout tetap konsisten --}}
            <div class="w-full lg:w-64 h-40 flex-shrink-0 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 border border-dashed border-gray-200 flex items-center justify-center">
                <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
            </div>
        @endif

        <div class="flex-1 flex flex-col">
            <div class="flex justify-between items-start">
                <div class="space-y-1">
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight group-hover:text-indigo-600 transition-colors">
                        {{ $portfolio->name }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full uppercase tracking-wider">
                            {{ $portfolio->project_field ?? 'Personal Project' }}
                        </span>
                        @if($portfolio->progressStatus)
                            <span class="text-gray-300">•</span>
                            <span class="text-sm text-gray-500 font-medium italic">{{ $portfolio->progressStatus->name }}</span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if ($isOwner)
                    <div class="flex gap-1 bg-gray-50 p-1 rounded-xl opacity-0 group-hover:opacity-100 transition-all translate-x-2 group-hover:translate-x-0">
                        <a href="{{ route('portfolios.edit', $portfolio->id) }}" class="w-9 h-9 flex items-center justify-center hover:bg-white hover:shadow-sm rounded-lg text-gray-400 hover:text-amber-500 transition-all">
                            <i class="fa-solid fa-pencil text-sm"></i>
                        </a>
                        <form action="{{ route('portfolios.destroy', $portfolio->id) }}" method="POST" onsubmit="return confirm('Hapus portfolio ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 flex items-center justify-center hover:bg-white hover:shadow-sm rounded-lg text-gray-400 hover:text-red-500 transition-all">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Meta Info --}}
            <div class="flex items-center gap-3 mt-4 text-sm font-medium text-gray-400">
                <i class="fa-regular fa-calendar-alt text-indigo-400"></i>
                <span>
                    {{ $startDate ? $startDate->translatedFormat('F Y') : 'N/A' }} – 
                    {{ $isOngoing ? 'Sekarang' : $endDate->translatedFormat('F Y') }}
                </span>
                @if($duration)
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                    <span class="text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md text-[11px] uppercase tracking-tighter">{{ $duration }}</span>
                @endif
            </div>

            {{-- Description (Dibuat lebih nyaman dibaca) --}}
            <div class="mt-4 text-gray-600 text-[15px] leading-relaxed line-clamp-2 group-hover:line-clamp-none transition-all duration-500">
                {{ $portfolio->description }}
            </div>

            <div class="mt-auto pt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                {{-- Tools Badges --}}
                <div class="flex flex-wrap gap-1.5">
                    @foreach($portfolio->tools as $pTool)
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-indigo-300 hover:bg-indigo-50/30 transition-colors">
                            @if($pTool->tool->image)
                                <img src="{{ asset('storage/' . $pTool->tool->image) }}" class="w-3.5 h-3.5 object-contain">
                            @endif
                            <span class="text-[11px] font-bold text-gray-700 uppercase tracking-tight">{{ $pTool->tool->name }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Link Akses --}}
                @if($portfolio->access_link)
                    <a href="{{ $portfolio->access_link }}" target="_blank" 
                       class="inline-flex items-center gap-2 px-5 py-2 bg-gray-900 hover:bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-gray-200 hover:shadow-indigo-200 transition-all duration-300 active:scale-95">
                        <span>LIHAT PROYEK</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>