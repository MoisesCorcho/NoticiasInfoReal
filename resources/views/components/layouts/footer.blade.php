@php
    // Obtenemos las categorías principales para el footer.
    // Limitamos a 6 para mantener el diseño limpio.
    $footerCategories = \App\Models\Category::whereNull('parent_id')
        ->orderBy('name')
        ->take(6)
        ->get();
@endphp

<footer
    class="bg-[#18181C] [html[data-theme=light]_&]:bg-black text-gray-300 [html[data-theme=light]_&]:text-gray-300 py-12 mt-12 transition-colors duration-200">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            {{-- Columna 1: Sobre Nosotros --}}
            <div class="col-span-1 md:col-span-2">
                <div class="mb-4">
                    <img src="{{ asset('images/new_logos/Logo InfoReal Horizontal blanco-01.png') }}" alt="InfoReal"
                        class="h-18 w-auto [html[data-theme=light]_&]:hidden" width="224" height="64"
                        decoding="async" />
                    <img src="{{ asset('images/new_logos/Logo InfoReal Horizontal blanco-01.png') }}" alt="InfoReal"
                        class="h-18 w-auto hidden [html[data-theme=light]_&]:block" width="224" height="64"
                        decoding="async" />
                </div>
                <p class="text-sm leading-relaxed mb-4">
                    Somos tu fuente confiable de noticias regionales, nacionales e internacionales. Comprometidos
                    con la
                    verdad y el periodismo de calidad desde Córdoba para el mundo.
                </p>
                {{-- Redes Sociales --}}
                <div class="flex space-x-4">
                    {{-- ... (iconos de redes sociales sin cambios) ... --}}
                    <a href="https://www.facebook.com/share/1EujWFnsEJ/?mibextid=wwXIfr"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-400 hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors"
                        aria-label="Facebook">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    {{-- <a href="#"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-white [html[data-theme=light]_&]:hover:text-gray-900 transition-colors"
                        aria-label="X (Twitter)">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M18.244 2H21.5l-7.57 8.652L22 22h-6.937l-4.84-6.325L4.7 22H1.44l8.095-9.24L2 2h7.063l4.379 5.753L18.244 2zm-1.216 17.271h1.904L7.064 4.601H5.005l12.023 14.67z" />
                        </svg>
                    </a> --}}
                    <a href="https://www.instagram.com/inforealnoticias?igsh=eG94aTlkcXAzZ2Iz"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-400 hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors"
                        aria-label="Instagram">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.148 3.227-1.667 4.77-4.919 4.919-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.07-1.646-.07-4.85s.012-3.584.07-4.85c.148-3.227 1.667-4.77 4.919-4.919C7.15 2.175 7.53 2.163 12 2.163zM12 0C8.74 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.74 0 12s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.74 24 12 24s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98C23.986 15.667 24 15.26 24 12s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98C15.667.014 15.26 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16.202a4.202 4.202 0 110-8.404 4.202 4.202 0 010 8.404zm6.406-11.845a1.44 1.44 0 100 2.88 1.44 1.44 0 000-2.88z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    {{-- <a href="#"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-white [html[data-theme=light]_&]:hover:text-gray-900 transition-colors"
                        aria-label="YouTube">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a> --}}
                </div>
            </div>

            {{-- Columna 2: Secciones DINÁMICAS --}}
            <div>
                <h3
                    class="text-white [html[data-theme=light]_&]:text-white text-sm font-bold uppercase tracking-wider mb-4 transition-colors duration-200">
                    Secciones</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('home') }}"
                            class="hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors">Inicio</a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}"
                            class="hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors">Sobre
                            Nosotros</a>
                    </li>
                    <li class="py-2">
                        <div class="h-px w-12 bg-red-primary"></div>
                    </li>
                    @foreach($footerCategories as $category)
                        <li>
                            <a href="{{ route('category.show', $category->slug) }}"
                                class="hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{--
        Barra inferior de Copyright
        --}}
        <div
            class="border-t border-white/10 [html[data-theme=light]_&]:border-gray-700 pt-8 text-center text-sm text-gray-500 [html[data-theme=light]_&]:text-gray-400 transition-colors duration-200">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            <p class="mt-2">Desarrollado con ❤️ para el mejor periodismo.</p>
        </div>
    </div>
</footer>