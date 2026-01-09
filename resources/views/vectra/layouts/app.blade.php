<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Crevolio Vectra</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
    </style>
</head>
<body class="bg-[#F8F9FB] text-gray-900 font-sans">

    <div class="flex flex-col h-full w-full"> 
        
        <header class="h-16 w-full bg-white border-b border-gray-200 shrink-0 z-50">
            @include('vectra.partials.topbar')
        </header>

        <div class="flex flex-1 min-h-0 w-full">
            
            <aside class="w-20 bg-white border-r border-gray-200 flex flex-col shrink-0">
                @include('vectra.partials.sidebar')
            </aside>

            <main id="main-content" class="flex-1 relative min-h-0 w-full bg-[#F8F9FB]">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>