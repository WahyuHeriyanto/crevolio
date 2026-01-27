<x-public-layout>

    {{-- SECTION 1: HERO --}}
    <section class="min-h-screen w-full flex flex-col items-center justify-center text-center px-6 relative overflow-hidden" 
             style="background-color: #05010d;">
        
        <div class="stars-container" style="z-index: 1;"></div>
        
        <div class="absolute inset-0" style="z-index: 2;">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-purple-900/10 rounded-full blur-[150px]"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-indigo-900/10 rounded-full blur-[100px]"></div>
        </div>

        <div class="max-w-5xl relative pt-20" style="z-index: 10;"> 
            <h1 class="text-[32px] md:text-[48px] font-semibold leading-[1.2] tracking-tight text-white mb-4">
                Don't build alone.<br>
                Find the perfect collaborators<br>
                for your projects
            </h1>
        </div>

        <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-black to-transparent" style="z-index: 3;"></div>
    </section>

    {{-- SECTION 2: TOP CREVOLIANS --}}
    <section class="py-24" 
             style="background: linear-gradient(to bottom, #000000 50%, #ffffff 50%);">
        <div class="max-w-7xl mx-auto px-6 text-center">
            {{-- Font Size 40 (text-[40px]), Medium (font-medium) --}}
            <h2 class="text-[28px] md:text-[40px] font-medium mb-20 text-white relative" style="z-index: 10;">
                Our Top Crevolians
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12 relative" style="z-index: 20;">
                @for ($i = 0; $i < 3; $i++)
                <div class="bg-white rounded-[2rem] p-8 flex items-center gap-5 shadow-2xl transition-transform hover:scale-105 border border-gray-50">
                    <div class="w-20 h-20 rounded-full bg-gray-200 flex-shrink-0 overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=User+{{ $i }}&background=random" class="w-full h-full object-cover">
                    </div>
                    <div class="text-left">
                        <h4 class="text-black font-bold text-xl">Wahyu Heriyanto</h4>
                        <p class="text-gray-500 text-base">Mobile App Developer</p>
                    </div>
                </div>
                @endfor
            </div>
            
            <p class="text-black-400 text-[22px] mt-16 font-normal relative" style="z-index: 10;">
                100+ Developers, Designers, and more roles are ready to join
            </p>
        </div>
    </section>

    {{-- SECTION 3: CREATE PROJECT CTA (Image 2 Bottom) --}}
    <section class="pb-24 bg-white flex justify-center">
        <a href="{{ route('login') }}" class="px-12 py-5 bg-black text-white text-xl font-bold rounded-full hover:scale-110 transition-transform shadow-2xl">
            CREATE PROJECT
        </a>
    </section>

    {{-- SECTION 4: DISCOVER PROJECTS (Image 3) --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h3 class="text-2xl md:text-[40px] font-medium mb-4">Or do you want to join a project?</h3>
                <h2 class="text-5xl md:text-[48px] font-black">Discover Projects</h2>
            </div>

            {{-- Grid Project --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($projects as $project)
                <div class="border border-gray-200 rounded-[2.5rem] p-6 shadow-sm hover:shadow-xl transition-all group">
                    {{-- Status & Type --}}
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-sm uppercase tracking-widest">OPEN <span class="text-gray-400 font-normal ml-2">Ends in 14 days</span></span>
                        <span class="px-4 py-1 border border-black rounded-lg text-xs font-bold uppercase tracking-widest">
                            {{ $project->detail->is_paid ? 'PAID' : 'VOLUNTEER' }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-2xl font-bold mb-6 leading-tight group-hover:text-indigo-600 transition">
                        {{ $project->name }}
                    </h3>

                    {{-- Details --}}
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase mb-2 tracking-tighter">Looking for:</p>
                            <ul class="text-sm font-medium space-y-1">
                                <li>1 UI/UX Designer</li>
                                <li>1 Technical Writer</li>
                            </ul>
                        </div>
                        <div class="text-right">
                             <div class="flex items-center gap-2 justify-end mb-4">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-dollar-sign text-xs"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Start from</p>
                                    <p class="text-sm font-bold">Rp1.000.000</p>
                                </div>
                             </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 border-t pt-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($project->owner->name) }}" class="w-8 h-8 rounded-full">
                        <span class="text-sm font-bold text-gray-700">{{ $project->owner->name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SECTION 5: FINAL CTA --}}
    <section class="bg-black text-center">

        {{-- Bagian atas tetap putih --}}
        <div class="bg-white text-black pt-32 pb-32">
            <h2 class="text-4xl md:text-[48px] font-semibold mb-5">
                One Account,
            </h2>
            <h2 class="text-4xl md:text-[48px] font-semibold mb-16">
                Endless Opportunities
            </h2>
            
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="flex flex-col items-center">
                    <div class="mb-6"><i class="fa-solid fa-right-to-bracket text-5xl"></i></div>
                    <h4 class="text-2xl font-bold mb-2">Create Profile</h4>
                    <p class="text-gray-500">Show your skills & roles</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="mb-6"><i class="fa-solid fa-rocket text-5xl"></i></div>
                    <h4 class="text-2xl font-bold mb-2">Start Project</h4>
                    <p class="text-gray-500">Find the best collaborators</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="mb-6"><i class="fa-solid fa-users text-5xl"></i></div>
                    <h4 class="text-2xl font-bold mb-2">Join Project</h4>
                    <p class="text-gray-500">Build your portfolio & network</p>
                </div>
            </div>
        </div>

        {{-- Final Black Box --}}
        <div class="w-full bg-black py-32 text-white">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Be Crevolian Now!</h2>
            <p class="text-xl text-gray-400 mb-12">Endless opportunities are waiting!</p>
            
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}" class="px-10 py-4 bg-white text-black font-bold rounded-full hover:scale-105 transition-all">
                    CREATE ACCOUNT
                </a>
                <a href="{{ route('login') }}" class="px-10 py-4 border border-white text-white font-bold rounded-full hover:bg-white hover:text-black transition-all">
                    LOGIN
                </a>
            </div>
        </div>

    </section>
</x-public-layout>