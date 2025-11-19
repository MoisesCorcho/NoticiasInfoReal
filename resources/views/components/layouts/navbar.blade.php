@php
    use Illuminate\Support\Facades\Storage;

    // Obtenemos las categorías raíz para la barra de navegación con artículos recientes
    $rootCategories = \App\Models\Category::whereNull('parent_id')
        ->with([
            'children',
            'articles' => fn ($query) => $query
                ->published()
                ->latest('published_at')
                ->limit(4),
        ])
        ->get();
@endphp

{{-- 
  Contenedor principal del header
--}}
<header x-data="{ mobileMenuOpen: false, searchOpen: false }" class="bg-[#101014] [html[data-theme=light]_&]:bg-white sticky top-0 z-50 border-b border-white/10 [html[data-theme=light]_&]:border-gray-200 shadow-[0_18px_45px_rgba(0,0,0,0.45)] [html[data-theme=light]_&]:shadow-sm transition-colors duration-200">
    
    {{-- BARRA PRINCIPAL (Logo a la izquierda, Iconos a la derecha) --}}
    <div class="max-w-screen-xl mx-auto px-4 relative flex items-center justify-between h-20">

        {{-- Lado Izquierdo: Logo --}}
        <div class="flex-1 flex justify-start items-center">
            <a href="{{ route('home') }}" class="block relative pb-2 border-b-2 border-[#d71935]">
                <span class="sr-only">{{ config('app.name') }}</span>
                <img
                    src="{{ asset('images/new_logos/Logo InfoReal 23.png') }}"
                    alt="InfoReal"
                    class="h-18 w-auto [html[data-theme=light]_&]:hidden"
                    width="224"
                    height="64"
                    decoding="async"
                />
                <img
                    src="{{ asset('images/new_logos/Logo InfoReal Horizontal negro-01.png') }}"
                    alt="InfoReal"
                    class="h-18 w-auto hidden [html[data-theme=light]_&]:block"
                    width="224"
                    height="64"
                    decoding="async"
                />
            </a>
        </div>

        {{-- Lado Derecha: Buscador y Menú Hamburguesa --}}
        <div class="flex-1 flex justify-end items-center gap-2">
            
            {{-- Botón Toggle Tema --}}
            <button id="theme-toggle" class="p-2 text-[#d71935] hover:text-white [html[data-theme=light]_&]:hover:text-[#d71935] focus:outline-none transition-colors duration-200" aria-label="Cambiar tema">
                <svg id="theme-icon-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                </svg>
                <svg id="theme-icon-light" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                </svg>
            </button>
            
            {{-- Botón Buscador --}}
            <button @click="searchOpen = !searchOpen" class="p-2 text-[#d71935] hover:text-white [html[data-theme=light]_&]:hover:text-[#d71935] focus:outline-none transition-colors duration-200" aria-label="Buscar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
            
            {{-- Botón Menú Hamburguesa --}}
            <button @click="mobileMenuOpen = true" class="p-2 text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-white [html[data-theme=light]_&]:hover:text-gray-900 focus:outline-none bg-[#333233] [html[data-theme=light]_&]:bg-gray-100 hover:bg-[#d71935] rounded-md transition-colors duration-200" aria-label="Abrir menú">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    {{-- 
      BARRA DE CATEGORÍAS 
    --}}
    <nav class="hidden md:block border-t border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="flex items-stretch justify-start h-12 bg-[#333233] [html[data-theme=light]_&]:bg-gray-100 relative transition-colors duration-200">
                {{-- Enlace de Home --}}
                <a href="{{ route('home') }}" 
                   class="relative flex items-center h-full font-medium text-sm tracking-[0.32em] uppercase px-4 transition-colors duration-200
                          {{ request()->is('/') ? 'text-white bg-[#d71935]' : 'text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-white [html[data-theme=light]_&]:hover:text-gray-900 hover:bg-[#d71935]' }}">
                    Home
                </a>

                {{-- Bucle de Categorías --}}
                @foreach ($rootCategories as $category)
                    <div class="static group h-full">
                        <a href="{{ route('category.show', $category->slug) }}" 
                           class="relative flex items-center h-full font-medium text-sm tracking-[0.32em] uppercase px-4 transition-colors duration-200
                                  {{ (request()->routeIs('category.show') && request()->route('slug') === $category->slug) ? 'text-white bg-[#d71935]' : 'text-gray-400 [html[data-theme=light]_&]:text-gray-600 group-hover:text-white [html[data-theme=light]_&]:group-hover:text-gray-900 group-hover:bg-[#d71935]' }}">
                            {{ $category->name }}
                        </a>

                        @if ($category->articles->isNotEmpty())
                            <div class="pointer-events-none invisible opacity-0 group-hover:visible group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200">
                                <div class="absolute top-full left-0 right-0">
                                    <div class="w-full bg-[#333233] [html[data-theme=light]_&]:bg-white border border-white/10 [html[data-theme=light]_&]:border-gray-200 shadow-2xl rounded-xl overflow-hidden z-40 p-4 transition-colors duration-200">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                        @foreach ($category->articles as $article)
                                            <a href="{{ route('article.show', $article->slug) }}" class="group/article flex flex-col min-w-0 rounded-lg overflow-hidden bg-[#333233] [html[data-theme=light]_&]:bg-gray-50 hover:bg-[#4a494a] [html[data-theme=light]_&]:hover:bg-gray-100 transition-colors duration-200">
                                                @if ($article->featured_image_url)
                                                    <div class="relative h-32 sm:h-36 w-full overflow-hidden">
                                                        <img
                                                            src="{{ Storage::url($article->featured_image_url) }}"
                                                            alt="{{ $article->title }}"
                                                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover/article:scale-105"
                                                        >
                                                    </div>
                                                @endif
                                                <div class="p-4 flex flex-col gap-2">
                                                    <span class="text-xs uppercase tracking-widest text-gray-400 [html[data-theme=light]_&]:text-gray-500">{{ $category->name }}</span>
                                                    <p class="text-sm font-semibold text-white [html[data-theme=light]_&]:text-gray-900 leading-snug line-clamp-3 group-hover/article:underline decoration-[#d71935] decoration-2 underline-offset-2 transition-colors duration-200">
                                                        {{ $article->title }}
                                                    </p>
                                                    @if ($article->published_at)
                                                        <span class="text-xs text-gray-400 [html[data-theme=light]_&]:text-gray-500">
                                                            {{ $article->published_at->translatedFormat('M d, Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- DESPLEGABLE DEL BUSCADOR --}} 
    <div
        x-show="searchOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.away="searchOpen = false"
        class="absolute top-20 left-0 w-full bg-[#333233] [html[data-theme=light]_&]:bg-white shadow-2xl z-40 p-4 border-t border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200"
        style="display: none;"
    >
        <div class="max-w-screen-xl mx-auto">
            <form action="{{ route('search') }}" method="GET" class="flex">
                <x-ui.text-input
                    type="text"
                    name="q"
                    placeholder="¿Qué estás buscando?"
                    class="w-full rounded-r-none border-r-0"
                    autofocus 
                />
                <button type="submit" class="bg-[#d71935] text-white px-4 hover:bg-red-700 transition-colors rounded-l-none rounded-r-md flex items-center cursor-pointer">
                    <span class="hidden md:inline mr-2">Buscar</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- MENÚ LATERAL MÓVIL (Off-canvas) --}}
    <div
        x-show="mobileMenuOpen"
        class="fixed inset-0 z-50 flex"
        style="display: none;"
    >
        {{-- Overlay oscuro --}}
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileMenuOpen = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        ></div>

        {{-- Panel del menú --}}
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative max-w-xs w-full bg-[#333233] [html[data-theme=light]_&]:bg-white border-r border-white/10 [html[data-theme=light]_&]:border-gray-200 shadow-2xl py-4 pb-12 flex flex-col overflow-y-auto h-full transition-colors duration-200"
        >
            <div class="px-4 flex items-center justify-between pb-4 border-b border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ asset('images/logos/Isotipo InfoReal Blanco.png') }}"
                        alt="InfoReal isotipo"
                        class="h-8 w-auto [html[data-theme=light]_&]:hidden"
                        width="64"
                        height="64"
                        decoding="async"
                    />
                    <img
                        src="{{ asset('images/logos/Isotipo InfoReal Negro.png') }}"
                        alt="InfoReal isotipo"
                        class="h-8 w-auto hidden [html[data-theme=light]_&]:block"
                        width="64"
                        height="64"
                        decoding="async"
                    />
                    <span class="text-xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 transition-colors duration-200">Categorías</span>
                </div>
                <button @click="mobileMenuOpen = false" class="-mr-2 p-2 text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-white [html[data-theme=light]_&]:hover:text-gray-900 focus:outline-none transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Aquí se cargan las categorías en el menú móvil --}}
            <div class="mt-4 px-4">
                <x-ui.category-dropdown :categories="$rootCategories" />
            </div>
        </div>
    </div>
</header>