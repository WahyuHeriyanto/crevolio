<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portfolio - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } body { -webkit-print-color-adjust: exact; } }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white p-10">
    <div class="max-w-4xl mx-auto">
        {{-- Header Profil --}}
        <div class="flex items-center gap-8 mb-10 pb-10 border-b-2 border-gray-100">
            <img src="{{ asset('storage/' . ($user->profile->photo_profile ?? 'defaults/avatar.png')) }}" 
                 class="w-32 h-32 rounded-[2.5rem] object-cover shadow-xl border-4 border-white ring-1 ring-gray-100">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $user->name }}</h1>
                <p class="text-xl text-indigo-600 font-bold mt-1">
                    {{ $user->profile->careerPosition->name ?? $user->profile->headline ?? 'Professional' }}
                </p>
                <div class="flex gap-4 mt-4 text-sm text-gray-500 font-medium">
                    <span><i class="fa-solid fa-envelope mr-1 text-gray-400"></i> {{ $user->email }}</span>
                    <span><i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> {{ $user->profile->location ?? 'Indonesia' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-10 mb-12">
            {{-- Column 1 & 2: About --}}
            <div class="col-span-2">
                <h2 class="text-xs uppercase tracking-[0.2em] font-black text-gray-400 mb-4">About Me</h2>
                <p class="text-gray-700 leading-relaxed">{{ $user->profile->description }}</p>
            </div>
            {{-- Column 3: Skills --}}
            <div class="col-span-1">
                <h2 class="text-xs uppercase tracking-[0.2em] font-black text-gray-400 mb-4">Skills/Expertises</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->profile->expertises as $exp)
                        <span class="text-[11px] font-bold text-indigo-700 bg-indigo-50 px-2 py-1 rounded">{{ $exp->expertise->name }}</span>
                    @endforeach
                </div>

                <h2 class="text-xs uppercase tracking-[0.2em] font-black text-gray-400 mt-6 mb-4">Tools & Stack</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($user->profile->tools as $uTool)
                        <span class="text-[11px] font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $uTool->tool->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Timeline Project --}}
        <div>
            <h2 class="text-xs uppercase tracking-[0.2em] font-black text-gray-400 mb-8 border-b pb-2">Project Experience</h2>
            <div class="relative pl-8 border-l-2 border-gray-100 space-y-12">
                @foreach($portfolios as $portfolio)
                <div class="relative">
                    <div class="absolute -left-[2.55rem] top-1 w-4 h-4 rounded-full bg-white border-4 border-indigo-600 shadow-sm"></div>
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $portfolio->name }}</h3>
                                    <p class="text-indigo-600 font-bold text-sm italic">{{ $portfolio->project_field }}</p>
                                </div>
                                <span class="text-[10px] font-black text-gray-400 uppercase">
                                    {{ \Carbon\Carbon::parse($portfolio->start_date)->format('M Y') }} - {{ $portfolio->end_date ? \Carbon\Carbon::parse($portfolio->end_date)->format('M Y') : 'Present' }}
                                </span>
                            </div>
                            <p class="mt-3 text-gray-600 text-sm leading-relaxed text-justify">{{ $portfolio->description }}</p>
                            <div class="flex flex-wrap gap-2 mt-4">
                                @foreach($portfolio->tools as $pTool)
                                    <span class="text-[9px] font-bold text-gray-400 border border-gray-200 px-2 py-0.5 rounded uppercase">
                                        {{ $pTool->tool->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @if($portfolio->medias->count() > 0)
                            <img src="{{ asset('storage/' . $portfolio->medias->first()->url) }}" class="w-40 h-28 rounded-xl object-cover border border-gray-100">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-20 text-center no-print">
            <button onclick="window.print()" class="px-12 py-4 bg-indigo-600 text-white rounded-full font-black shadow-xl">Download PDF</button>
        </div>
    </div>
</body>
</html>