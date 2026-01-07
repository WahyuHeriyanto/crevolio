<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
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
<body class="bg-white text-gray-900 antialiased overflow-x-hidden">

    <x-navbar />

    <main class="w-full">
        {{ $slot }}
    </main>

    <x-footer />

</body>
</html>
