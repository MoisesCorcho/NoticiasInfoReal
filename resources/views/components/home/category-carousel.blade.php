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
    <div class="flex items-center justify-between mb-6 border-b border-white/10">
        <h2 class="text-xl font-bold uppercase bg-[#d71935] text-white px-4 py-1 inline-block rounded-t-md">
            {{ $category->name }}
        </h2>
    </div>

    {{-- Contenedor del Carrusel --}}
    <div class="relative bg-[#18181C] p-4 rounded-lg border border-white/10">
        <div class="swiper" x-ref="carousel">
            <div class="swiper-wrapper">
                {{-- Iteramos sobre los artículos (¡nota que ahora usamos $articles!) --}}
                @forelse($articles as $article)
                    <div class="swiper-slide group relative aspect-[3/4] overflow-hidden rounded-md">
                        <a href="{{ route('article.show', $article->slug) }}" class="block w-full h-full">
                            <img src="{{ $article->featured_image_url ? Storage::url($article->featured_image_url) : 'https://placehold.co/300x400/18181C/333233?text=Art%C3%ADculo' }}"
                                 alt="{{ $article->title }}"
                                 onerror="this.src='https://placehold.co/300x400/18181C/333233?text=Error'"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent">
                                <div class="absolute bottom-0 p-3 text-white">
                                    <span class="inline-block bg-[#d71935] text-white text-[10px] font-bold px-1.5 py-0.5 rounded mb-1">
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
                        <div class="aspect-[3/4] flex items-center justify-center text-gray-500 bg-[#101014] rounded-md">
                            No hay noticias.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Botones de Navegación --}}
        <button x-ref="prev"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-2 bg-[#333233] text-white rounded-full shadow-md hover:bg-[#d71935] transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button x-ref="next"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-2 bg-[#333233] text-white rounded-full shadow-md hover:bg-[#d71935] transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                 stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>
    </div>
</section>