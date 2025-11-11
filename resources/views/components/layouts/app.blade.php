<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'El Notición Clone' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fuente opcional similar a noticias -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Header / Navegación -->
    <x-layouts.navbar />

    <main class="max-w-screen-xl mx-auto px-4 py-6 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer simple -->
    <x-layouts.footer />
</body>
</html>