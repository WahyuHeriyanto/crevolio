<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crevolio Vectra | Precision in Every Dimension</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes stars-move {
            from { transform: translateY(0); }
            to { transform: translateY(-1000px); }
        }

        @keyframes title-glow {
            0%, 100% { text-shadow: 0 0 20px rgba(255,255,255,0.2); }
            50% { text-shadow: 0 0 50px rgba(99, 102, 241, 0.5); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .space-bg {
            background: radial-gradient(circle at center, #1B2735 0%, #050608 100%);
            overflow: hidden;
        }

        /* Bintang Bergerak */
        .stars-container {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 200%;
            background: url('https://www.transparenttextures.com/patterns/stardust.png');
            opacity: 0.4;
            animation: stars-move 100s linear infinite;
            z-index: 0;
        }

        .headline-animate {
            animation: title-glow 4s ease-in-out infinite, float 6s ease-in-out infinite;
        }

        /* Efek Shine pada Button */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.6), transparent);
            transform: rotate(45deg);
            transition: 0.5s;
            left: -150%;
        }
        .btn-shine:hover::after {
            left: 150%;
        }

        .glow-card {
            box-shadow: 0 0 80px rgba(79, 70, 229, 0.15);
        }
    </style>
</head>
<body class="space-bg text-white antialiased">
    {{-- Background Layer --}}
    <div class="stars-container"></div>
    
    <main class="relative min-h-screen flex items-center justify-center overflow-hidden py-20">
        {{-- Ornament Planet/Nebula --}}
        <div class="absolute top-0 -left-20 w-96 h-96 bg-indigo-600/10 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-0 -right-20 w-96 h-96 bg-purple-600/10 rounded-full blur-[150px]"></div>

        <div class="max-w-5xl text-center z-10 px-6">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-[10px] font-bold tracking-[0.2em] uppercase mb-10 glow-card"
                 x-data x-intersect="$el.classList.add('animate-bounce')">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Vectra System Active
            </div>

            {{-- Headline dengan Animasi --}}
            <h1 class="headline-animate text-5xl md:text-9xl font-black tracking-tighter mb-8 leading-none bg-clip-text text-transparent bg-gradient-to-b from-white via-white to-indigo-900">
                Precision in Every Dimension
            </h1>

            <p class="text-lg md:text-2xl text-gray-400 mb-14 max-w-3xl mx-auto font-light leading-relaxed">
                Advanced weaponry designed to empower teams in crafting <span class="text-white font-medium italic underline decoration-indigo-500">awesome projects</span> with interstellar efficiency.
            </p>

            {{-- Get Started Button --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                @php
                    $targetUrl = Auth::check() ? route('vectra.dashboard') : 'https://crevolio.test/login';
                @endphp

                <a href="{{ $targetUrl }}" 
                   class="btn-shine group relative px-12 py-5 bg-white text-black font-black rounded-2xl transition-all transform hover:scale-110 active:scale-95 shadow-[0_0_50px_rgba(255,255,255,0.3)] hover:shadow-indigo-500/50">
                    <span class="relative z-10 flex items-center gap-2">
                        GET STARTED 
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                </a>

                <a href="#tech" class="text-gray-500 hover:text-indigo-400 transition-all font-bold tracking-widest text-sm uppercase">
                    Learn the Tech
                </a>
            </div>
        </div>

        {{-- Perbaikan Footer: Pakai Flex agar tidak menumpuk di mobile --}}
        <div class="absolute bottom-8 w-full flex flex-col items-center gap-2 pointer-events-none">
            <div class="w-12 h-[1px] bg-indigo-500/30"></div>
            <p class="text-[10px] text-gray-600 tracking-[0.4em] uppercase">
                Part of Crevolio Interstellar Ecosystem
            </p>
        </div>
    </main>
</body>
</html>