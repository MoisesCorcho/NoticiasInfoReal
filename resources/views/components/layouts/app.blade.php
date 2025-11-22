<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logos/Isotipo InfoReal Rojo.png') }}">

    <!-- Swiper.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fuente Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="bg-[#101014] [html[data-theme=light]_&]:bg-white text-gray-300 [html[data-theme=light]_&]:text-gray-900 font-sans antialiased transition-colors duration-200">
    <!-- Header / Navegación -->
    <x-layouts.navbar />

    <main class="max-w-screen-xl mx-auto px-4 py-6 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer simple -->
    <x-layouts.footer />
    
    <!-- Swiper.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>
