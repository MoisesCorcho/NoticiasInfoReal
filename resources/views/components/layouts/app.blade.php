<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logos/Isotipo InfoReal Rojo.png') }}">

    {{-- Meta tags Open Graph para compartir en redes sociales --}}
    @if(isset($article) && $article instanceof \App\Models\Article)
        @php
            $articleUrl = route('article.show', $article->slug);
            $articleImage = $article->featured_image_url 
                ? url(\Illuminate\Support\Facades\Storage::url($article->featured_image_url)) 
                : url(asset('images/logos/Isotipo InfoReal Rojo.png'));
            $articleDescription = $article->excerpt ?? $article->title;
        @endphp
        <meta property="og:type" content="article">
        <meta property="og:title" content="{{ $article->title }}">
        <meta property="og:description" content="{{ $articleDescription }}">
        <meta property="og:image" content="{{ $articleImage }}">
        <meta property="og:url" content="{{ $articleUrl }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        
        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $article->title }}">
        <meta name="twitter:description" content="{{ $articleDescription }}">
        <meta name="twitter:image" content="{{ $articleImage }}">
    @else
        {{-- Meta tags por defecto para otras páginas --}}
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:description" content="{{ config('app.name') }}">
        <meta property="og:image" content="{{ url(asset('images/logos/Isotipo InfoReal Rojo.png')) }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
    @endif

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
