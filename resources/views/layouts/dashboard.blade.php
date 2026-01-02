<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crevolio</title>
    @vite('resources/css/app.css')
    <script defer src="//unpkg.com/alpinejs"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-50">

    {{-- TOPBAR --}}
    @include('dashboard.partials.topbar')

    {{-- MAIN --}}
    <main class="pt-6">
        @yield('content')
    </main>

</body>
</html>
