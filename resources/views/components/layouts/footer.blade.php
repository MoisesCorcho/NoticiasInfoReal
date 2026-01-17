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
                    <a href="https://x.com/inforealnoticia?s=11"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-white [html[data-theme=light]_&]:hover:text-gray-900 transition-colors"
                        aria-label="X (Twitter)">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </a>
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
                    <a href="https://www.tiktok.com/@inforealnoticias?_r=1&_t=ZS-91tuIhWp9UW"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-400 hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors"
                        aria-label="TikTok">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="https://wa.me/573013075908" target="_blank" rel="noopener noreferrer"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-400 hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors"
                        aria-label="WhatsApp">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="mailto:inforealverdad@gmail.com"
                        class="text-gray-400 [html[data-theme=light]_&]:text-gray-400 hover:text-white [html[data-theme=light]_&]:hover:text-white transition-colors"
                        aria-label="Email">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a1.5 1.5 0 0 1-1.144 0L1.5 8.67Z" />
                            <path d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                        </svg>
                    </a>
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
