<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · Crevolio</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 h-screen flex flex-col" x-data="{ sidebarOpen: true }">

    <header class="h-16 bg-white border-b flex items-center justify-between px-6 z-50 flex-shrink-0">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:bg-gray-100 p-2 rounded">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <span class="font-bold text-xl tracking-tight text-gray-700">Crevolio</span>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm font-semibold text-gray-600 uppercase">{{ auth()->user()->name }}</span>
            <div class="w-10 h-10 rounded-full bg-gray-300 overflow-hidden border">
                <img src="{{ auth()->user()->profile?->photo_profile ? asset('storage/'.auth()->user()->profile->photo_profile) : asset('assets/images/photo-profile-default.png') }}" 
                     class="w-full h-full object-cover">
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        
        <aside 
            class="bg-black text-white transition-all duration-300 flex-shrink-0 flex flex-col"
            :class="sidebarOpen ? 'w-64' : 'w-20'"
        >
            <nav class="flex-1 pt-6 overflow-y-auto custom-scrollbar">
                <a href="{{ route('admin.master-users.index') }}" 
                   class="flex items-center gap-4 px-6 py-4 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.master-users.*') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                    <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Overview</span>
                </a>
                <a href="{{ route('admin.master-users.index') }}" 
                   class="flex items-center gap-4 px-6 py-4 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.master-users.*') ? 'bg-white/10 border-l-4 border-blue-500' : '' }}">
                    <i class="fa-solid fa-user-gear text-lg"></i>
                    <span x-show="sidebarOpen" class="font-medium whitespace-nowrap">Master User</span>
                </a>
            </nav>
        </aside>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                @yield('admin-content')
            </div>
        </main>

    </div>

</body>
</html>