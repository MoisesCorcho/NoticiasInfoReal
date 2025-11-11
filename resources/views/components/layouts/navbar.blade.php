@php
    // Obtenemos las categorías raíz para la barra de navegación
    $rootCategories = \App\Models\Category::whereNull('parent_id')
        ->with('children') // 'children.children' no es necesario para el primer nivel
        ->get();
@endphp

{{-- 
  Contenedor principal del header
  - x-data: Gestiona el estado del menú móvil y el desplegable de búsqueda
  - bg-gray-900: Fondo oscuro para la barra principal (logo/iconos)
  - sticky top-0 z-50: Fija el header completo en la parte superior
--}}
<header x-data="{ mobileMenuOpen: false, searchOpen: false }" class="bg-gray-900 sticky top-0 z-50 shadow-md">
    
    {{-- BARRA PRINCIPAL (Logo a la izquierda, Iconos a la derecha) --}}
    <div class="max-w-screen-xl mx-auto px-4 relative flex items-center justify-between h-16">

        {{-- Lado Izquierdo: Logo --}}
        <div class="flex-1 flex justify-start items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-white tracking-wide">
                {{ config('app.name') }}
            </a>
        </div>

        {{-- Lado Derecha: Buscador y Menú Hamburguesa --}}
        <div class="flex-1 flex justify-end items-center gap-2">
            
            {{-- Botón Buscador --}}
            <button @click="searchOpen = !searchOpen" class="p-2 text-gray-300 hover:text-white focus:outline-none" aria-label="Buscar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
            
            {{-- Botón Menú Hamburguesa --}}
            <button @click="mobileMenuOpen = true" class="p-2 text-gray-300 hover:text-white focus:outline-none" aria-label="Abrir menú">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>
    </div>

    {{-- BARRA DE CATEGORÍAS (Debajo de la barra principal) --}}
    <nav class="bg-gray-800 border-t border-gray-700 hidden md:block">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="flex items-center justify-start h-12 gap-8">
                {{-- Enlace de Home --}}
                <a href="{{ route('home') }}" 
                   class="font-medium text-white uppercase text-sm tracking-wider
                          {{ request()->is('/') ? 'text-red-500 font-bold' : 'text-gray-300 hover:text-white' }}
                          transition-colors duration-200">
                    Home
                </a>

                {{-- Bucle de Categorías --}}
                @foreach ($rootCategories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" 
                       class="font-medium text-white uppercase text-sm tracking-wider
                              {{-- Comprueba si la URL actual coincide con la categoría --}}
                              {{ request()->is('category/' . $category->slug . '*') ? 'bg-red-600 px-3 py-2 rounded-t-sm' : 'text-gray-300 hover:text-white' }}
                              transition-colors duration-200">
                        {{ $category->name }}
                    </a>
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
        class="absolute top-16 left-0 w-full bg-white shadow-lg z-40 p-4 border-t border-gray-100"
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
                <button type="submit" class="bg-red-600 text-white px-6 rounded-r-md hover:bg-red-700 transition flex items-center">
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
            class="fixed inset-0 bg-black bg-opacity-50"
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
            class="relative max-w-xs w-full bg-white shadow-xl py-4 pb-12 flex flex-col overflow-y-auto h-full"
        >
            <div class="px-4 flex items-center justify-between pb-4 border-b border-gray-200">
                <span class="text-xl font-bold text-gray-900">Categorías</span>
                <button @click="mobileMenuOpen = false" class="-mr-2 p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Aquí se cargan las categorías en el menú móvil --}}
            <div class="mt-4 px-4">
                {{-- Asumiendo que este componente está en /components/ui/category-dropdown.blade.php --}}
                <x-ui.category-dropdown :categories="$rootCategories" />
            </div>
        </div>
    </div>
</header>