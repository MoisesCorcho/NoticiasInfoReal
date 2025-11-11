@php
    // Obtenemos las categorías raíz con sus hijos cargados recursivamente.
    // Idealmente esto debería venir de un View Composer o Servicio para no ensuciar la vista.
    $rootCategories = \App\Models\Category::whereNull('parent_id')
        ->with('children.children') // Carga ansiosa para un par de niveles, ajusta según profundidad esperada
        ->get();
@endphp

<header x-data="{ mobileMenuOpen: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-4 relative flex items-center justify-between py-4">

        {{-- Botón Menú Hamburguesa (Izquierda) --}}
        <div class="flex-1 flex justify-start">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-gray-600 hover:text-red-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            {{-- En escritorio podrias poner otra cosa aquí o dejarlo vacío --}}
        </div>

        {{-- Título Centrado --}}
        <div class="absolute left-1/2 transform -translate-x-1/2">
            <a href="{{ route('home') }}" class="text-3xl font-bold text-red-700">
                El Notición
            </a>
        </div>

        {{-- Menú Derecha (Escritorio) --}}
        <div class="flex-1 flex justify-end">
            <div class="hidden md:flex space-x-6 text-sm uppercase font-semibold text-gray-600">
                {{-- Ejemplo estático, podrías hacerlo dinámico con $rootCategories si quisieras --}}
                <a href="#" class="hover:text-red-700 transition-colors">Región</a>
                <a href="#" class="hover:text-red-700 transition-colors">Deportes</a>
                <a href="#" class="hover:text-red-700 transition-colors">Nacional</a>
                <a href="#" class="hover:text-red-700 transition-colors">Política</a>
            </div>
        </div>
    </div>

    {{-- Menú Lateral Móvil (Off-canvas) --}}
    <div
        x-show="mobileMenuOpen"
        class="fixed inset-0 z-50 flex"
        style="display: none;" {{-- Evita parpadeo al cargar --}}
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
                <x-category-dropdown :categories="$rootCategories" />
            </div>
        </div>
    </div>
</header>