<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    body {
            font-family: 'Poppins', sans-serif;
        }
    @keyframes stars-move {
        from { transform: translateY(0); }
        to { transform: translateY(-1000px); }
    }

    .space-bg {
        background: radial-gradient(circle at center, #1B2735 0%, #050608 100%);
        position: relative;
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
        pointer-events: none;
    }

    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }
</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased overflow-x-hidden selection:bg-indigo-500 selection:text-white">
    <x-navbar />
    {{-- Hapus bg-white di main jika ada --}}
    <main class="w-full">
        {{ $slot }}
    </main>
    <x-footer />
</body>
</html>
