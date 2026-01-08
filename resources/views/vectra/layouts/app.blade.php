<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crevolio Vectra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Tambahin FontAwesome buat icon sidebar --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* Paksa main-content buat beneran nangkep scroll */
    #main-content {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Hilangkan scrollbar horizontal yang bikin geser kanan kiri */
    body, html {
        max-width: 100vw;
        overflow-x: hidden !important;
    }

    /* Bikin scrollbar keliatan biar lo gak bingung */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db; /* abu-abu terang */
        border-radius: 10px;
    }
</style>
    
</head>
<body class="bg-[#F8F9FB] antialiased overflow-hidden"> 

    <div class="flex flex-col h-screen w-full overflow-hidden"> 
        
        {{-- TOPBAR: Tetap --}}
        <header class="h-16 shrink-0 bg-white border-b border-gray-200 z-50">
            @include('vectra.partials.topbar')
        </header>

        {{-- AREA BAWAH TOPBAR --}}
        <div class="flex flex-1 min-h-0 w-full overflow-hidden">
            
            {{-- SIDEBAR: Tambahin 'flex flex-col' biar background putihnya ditarik sampe bawah --}}
            <aside class="w-20 shrink-0 bg-white border-r border-gray-200 h-full overflow-hidden flex flex-col">
                @include('vectra.partials.sidebar')
            </aside>

            {{-- MAIN CONTENT: Tetap seperti versi better lo --}}
            <main id="main-content" class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden bg-[#F8F9FB] custom-scrollbar">
                <div class="max-w-[1400px] mx-auto p-8 w-full">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>