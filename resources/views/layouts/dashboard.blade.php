<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crevolio</title>
    @vite('resources/css/app.css')
    <script defer src="//unpkg.com/alpinejs"></script>
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
