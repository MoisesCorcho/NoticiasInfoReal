@php
    $rootCategories = \App\Models\Category::whereNull('parent_id')
        ->with('children.children')
        ->get();
@endphp

<header x-data="{ mobileMenuOpen: false, searchOpen: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-4 relative flex items-center justify-between py-4">

        {{-- Lado Izquierdo: Menú Hamburguesa y Buscador --}}
        <div class="flex-1 flex justify-start items-center gap-1">
            {{-- Botón Menú Hamburguesa --}}
            <button @click="mobileMenuOpen = true" class="p-2 text-gray-600 hover:text-red-700 focus:outline-none" aria-label="Abrir menú">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            {{-- Botón Buscador --}}
            <button @click="searchOpen = !searchOpen" class="p-2 text-gray-600 hover:text-red-700 focus:outline-none" aria-label="Buscar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
        </div>

        {{-- Título Centrado --}}
        <div class="absolute left-1/2 transform -translate-x-1/2">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-red-700">
                El Notición
            </a>
        </div>

        {{-- Menú Derecha (Opcional) --}}
        <div class="flex-1 flex justify-end">
            <div class="hidden md:flex space-x-6 text-sm uppercase font-semibold text-gray-600">
                {{-- Enlaces rápidos opcionales --}}
            </div>
        </div>
    </div>

    {{-- Desplegable del Buscador --}}
    <div
        x-show="searchOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.away="searchOpen = false"
        class="absolute top-full left-0 w-full bg-white shadow-md z-40 p-4 border-t border-gray-100"
        style="display: none;"
    >
        <div class="max-w-screen-xl mx-auto">
            {{-- Reutilizamos el SearchWidget aquí, pero quizás necesitemos una versión simplificada sin el título h4 --}}
            {{-- Para hacerlo rápido y limpio, incrustamos el formulario directamente usando el componente Livewire si lo soporta, o un formulario estándar GET --}}
            <form action="{{ route('search') }}" method="GET" class="flex">
                <x-ui.text-input
                    type="text"
                    name="q"
                    placeholder="¿Qué estás buscando?"
                    class="w-full rounded-r-none border-r-0"
                    autofocus {{-- Para que el foco vaya directo al input al abrir --}}
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

    {{-- Menú Lateral Móvil (Off-canvas) - Sin cambios aquí --}}
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

            <div class="mt-4 px-4">
                <x-ui.category-dropdown :categories="$rootCategories" />
            </div>
        </div>
    </div>
</header>