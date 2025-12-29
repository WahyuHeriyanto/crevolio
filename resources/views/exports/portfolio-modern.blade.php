<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portfolio - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print { .no-print { display: none; } body { background: #f9fafb !important; } }
        body { font-family: 'Inter', sans-serif; background: #f9fafb; }
    </style>
</head>
<body class="p-0">
    <div class="max-w-6xl mx-auto bg-white min-h-screen shadow-2xl flex flex-col md:flex-row">
        {{-- Sidebar --}}
        <div class="w-full md:w-80 bg-gray-900 text-white p-10 flex flex-col">
            <img src="{{ asset('storage/' . ($user->profile->photo_profile ?? 'defaults/avatar.png')) }}" 
                 class="w-full aspect-square rounded-3xl object-cover mb-6 border-2 border-gray-800 p-1">
            
            <h1 class="text-2xl font-black leading-tight">{{ $user->name }}</h1>
            <p class="text-indigo-400 font-bold text-sm mt-2 uppercase tracking-widest">
                {{ $user->profile->careerPosition->name ?? $user->profile->headline }}
            </p>

            <div class="mt-10 space-y-8 flex-1">
                {{-- Skills --}}
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-black mb-4">Skills/Expertises</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->profile->expertises as $exp)
                            <span class="text-[10px] bg-gray-800 text-gray-300 px-2 py-1 rounded font-bold">{{ $exp->expertise->name }}</span>
                        @endforeach
                    </div>
                </div>

                {{-- Tools --}}
                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-black mb-4">Tools Stack</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->profile->tools as $uTool)
                            <span class="text-[10px] border border-gray-700 text-indigo-300 px-2 py-1 rounded font-bold">{{ $uTool->tool->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-500 font-black mb-1">Email</p>
                    <p class="text-sm font-medium text-gray-300 truncate">{{ $user->email }}</p>
                </div>
            </div>
            
            <p class="text-[9px] text-gray-600 font-bold mt-10 italic">Generated via Crevolians Portfolio</p>
        </div>

        {{-- Main --}}
        <div class="flex-1 p-12 bg-white">
            <div class="mb-12 border-b-4 border-gray-900 pb-8">
                <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tighter italic uppercase">Personal Bio.</h2>
                <p class="text-gray-500 text-lg leading-relaxed">{{ $user->profile->description }}</p>
            </div>

            <div class="grid grid-cols-1 gap-8">
                @foreach($portfolios as $portfolio)
                <div class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100 relative group">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-[10px] font-black bg-gray-900 text-white px-4 py-1 rounded-full uppercase italic">
                            {{ $portfolio->project_field }}
                        </span>
                        <p class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">
                            {{ \Carbon\Carbon::parse($portfolio->start_date)->format('Y') }}
                        </p>
                    </div>

                    <h3 class="text-2xl font-black text-gray-900 mb-4">{{ $portfolio->name }}</h3>
                    
                    @if($portfolio->medias->count() > 0)
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        @foreach($portfolio->medias->take(2) as $media)
                            <img src="{{ asset('storage/' . $media->url) }}" class="rounded-2xl h-40 w-full object-cover shadow-sm">
                        @endforeach
                    </div>
                    @endif

                    <p class="text-gray-600 leading-relaxed mb-6">{{ $portfolio->description }}</p>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($portfolio->tools as $pTool)
                            <span class="text-[9px] font-black text-indigo-600 bg-white shadow-sm border border-gray-100 px-3 py-1 rounded-lg uppercase">
                                #{{ $pTool->tool->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 no-print">
                <button onclick="window.print()" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl hover:scale-[1.02] transition-all shadow-2xl">
                    PRINT PORTFOLIO
                </button>
            </div>
        </div>
    </div>
</body>
</html>