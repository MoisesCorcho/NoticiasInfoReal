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
    <header class="bg-white shadow-sm sticky top-0 z-50">
        {{-- Usamos max-w-screen-xl en lugar de container para limitar el ancho en pantallas muy grandes --}}
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="text-3xl font-bold text-red-700">El Notición</div>
                <div class="hidden md:flex space-x-4 text-sm uppercase font-semibold text-gray-600">
                    <!-- Puedes hacer esto dinámico luego con Category::all() -->
                    <a href="#" class="hover:text-red-700">Región</a>
                    <a href="#" class="hover:text-red-700">Deportes</a>
                    <a href="#" class="hover:text-red-700">Nacional</a>
                    <a href="#" class="hover:text-red-700">Política</a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-screen-xl mx-auto px-4 py-6 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer simple -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-screen-xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} El Notición Clone. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>