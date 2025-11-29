@props(['category', 'articles'])

<section id="category-carousel" class="overflow-hidden" x-data="{
    initSwiper: function() {
        new Swiper(this.$refs.carousel, {
            loop: true,
            slidesPerView: 2,
            spaceBetween: 16,
            navigation: {
                nextEl: this.$refs.next,
                prevEl: this.$refs.prev,
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 20 },
                768: { slidesPerView: 4, spaceBetween: 24 },
                1024: { slidesPerView: 5, spaceBetween: 32 },
            }
        });
    }
}" x-init="initSwiper()">

    {{-- Título de la sección (dinámico) --}}
    <div class="flex items-center mb-6">
        {{-- Línea izquierda --}}
        <div class="flex-grow bg-red-primary h-0.5"></div>

        {{-- Título --}}
        <h2
            class="text-lg font-bold uppercase text-white [html[data-theme=light]_&]:text-gray-900 mx-4 flex-shrink-0 transition-colors duration-200 flex items-center gap-2">
            <img src="{{ asset('images/logos/Isotipo InfoReal Blanco.png') }}" alt="InfoReal"
                class="h-6 w-auto [html[data-theme=light]_&]:hidden">
            <img src="{{ asset('images/logos/Isotipo InfoReal Negro.png') }}" alt="InfoReal"
                class="h-6 w-auto hidden [html[data-theme=light]_&]:block">
            <span>InfoReal {{ $category->name }}</span>
        </h2>

        {{-- Línea derecha --}}
        <div class="flex-grow bg-red-primary h-0.5"></div>
    </div>

    {{-- Contenedor del Carrusel --}}
    <div
        class="relative bg-[#18181C] [html[data-theme=light]_&]:bg-white p-4 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
        <div class="swiper" x-ref="carousel">
            <div class="swiper-wrapper">
                {{-- Iteramos sobre los artículos --}}
                @forelse($articles as $article)
                    <div class="swiper-slide group relative aspect-[3/4] overflow-hidden rounded-md">
                        <a href="{{ route('article.show', $article->slug) }}" class="block w-full h-full">
                            <img src="{{ $article->featured_image_url ? Storage::url($article->featured_image_url) : 'https://placehold.co/300x400/18181C/333233?text=Art%C3%ADculo' }}"
                                alt="{{ $article->title }}"
                                onerror="this.src='https://placehold.co/300x400/18181C/333233?text=Error'"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent">
                                <div class="absolute bottom-0 p-3 text-white">
                                    <span
                                        class="inline-block bg-red-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded mb-1">
                                        {{ $article->category->name }}
                                    </span>
                                    <h4 class="font-bold text-sm leading-tight line-clamp-2">
                                        <span class="hover:underline">
                                            {{ $article->title }}
                                        </span>
                                    </h4>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div
                            class="aspect-[3/4] flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 bg-[#101014] [html[data-theme=light]_&]:bg-gray-100 rounded-md transition-colors duration-200">
                            No hay noticias.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Botones de Navegación --}}
        <button x-ref="prev"
            class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-2 bg-[#333233] [html[data-theme=light]_&]:bg-gray-200 text-white [html[data-theme=light]_&]:text-gray-900 rounded-full shadow-md hover:bg-red-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button x-ref="next"
            class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-2 bg-[#333233] [html[data-theme=light]_&]:bg-gray-200 text-white [html[data-theme=light]_&]:text-gray-900 rounded-full shadow-md hover:bg-red-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>
</section>