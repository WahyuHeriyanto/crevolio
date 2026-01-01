<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
