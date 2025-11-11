<div class="space-y-12">
    {{-- SECCIÓN 1: HERO (Noticia principal grande + 2 laterales) --}}
    @if ($heroArticles->isNotEmpty())
        <section id="hero" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Noticia Principal (ocupa 2 columnas en pantallas grandes) --}}
            <div class="lg:col-span-2 group relative h-96 overflow-hidden rounded-lg shadow-lg">
                @if ($heroArticles[0]->featured_image_url)
                    <img src="{{ Storage::url($heroArticles[0]->featured_image_url) }}"
                        alt="{{ $heroArticles[0]->title }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="w-full h-full bg-gray-300 flex items-center justify-center">Sin Imagen</div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                    <div class="absolute bottom-0 p-6 text-white">
                        <span class="inline-block bg-red-600 text-xs font-bold px-2 py-1 mb-2 rounded">
                            {{ $heroArticles[0]->category->name ?? 'General' }}
                        </span>
                        <h1 class="text-3xl font-bold leading-tight hover:underline">
                            <a href="{{ route('article.show', $heroArticles[0]->slug) }}">
                                {{ $heroArticles[0]->title }}
                            </a>
                        </h1>
                        <p class="text-gray-300 mt-2 text-sm line-clamp-2">{{ $heroArticles[0]->excerpt }}</p>
                        <p class="text-gray-400 text-xs mt-3">{{ $heroArticles[0]->published_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Noticias Secundarias del Hero (columna derecha) --}}
            <div class="space-y-6">
                @foreach ($heroArticles->skip(1) as $article)
                    <div class="relative h-[184px] overflow-hidden rounded-lg shadow group">
                        @if ($article->featured_image_url)
                            <img src="{{ Storage::url($article->featured_image_url) }}" alt="{{ $article->title }}"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                            <div class="absolute bottom-0 p-4 text-white">
                                <span class="bg-blue-600 text-[10px] font-bold px-1.5 py-0.5 rounded">
                                    {{ $article->category->name ?? 'News' }}
                                </span>
                                <h3 class="font-bold text-lg leading-snug mt-1 hover:underline">
                                    <a href="{{ route('article.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- SECCIÓN 2: ÚLTIMAS NOTICIAS (Grid estándar) --}}
    <section id="latest-news">
        <div class="flex items-center justify-between mb-6 border-b border-gray-200">
            <h2 class="text-xl font-bold uppercase text-gray-800 relative inline-block py-2">
                Últimas Noticias
                <span class="absolute bottom-0 left-0 w-1/2 h-1 bg-red-600"></span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($latestArticles as $article)
                <x-article-card :article="$article" />
            @endforeach
        </div>
    </section>

    {{-- SECCIÓN 3: CATEGORÍAS ESPECÍFICAS (Dos columnas) --}}
    @if ($featuredCategory)
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
            <div class="flex items-center justify-between mb-6 border-b-2" style="border-color: #009961;">
                {{-- Color verde de tu imagen --}}
                <h2 class="text-xl font-bold uppercase bg-[#009961] text-white px-4 py-1 inline-block">
                    {{ $featuredCategory->name }}
                </h2>
            </div>

            {{-- Contenedor del Carrusel --}}
            <div class="relative bg-gray-900 p-4 rounded-lg">
                <div class="swiper" x-ref="carousel">
                    <div class="swiper-wrapper">
                        {{-- Iteramos sobre los artículos destacados --}}
                        @forelse($featuredCategoryArticles as $article)
                            <div class="swiper-slide group relative aspect-[3/4] overflow-hidden rounded-md">
                                <img src="{{ Storage::url($article->featured_image_url) }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent">
                                    <div class="absolute bottom-0 p-3 text-white">
                                        <span
                                            class="inline-block bg-[#009961] text-white text-[10px] font-bold px-1.5 py-0.5 rounded mb-1">
                                            {{ $article->category->name }}
                                        </span>
                                        <h4 class="font-bold text-sm leading-tight line-clamp-2">
                                            <a href="{{ route('article.show', $article->slug) }}"
                                                class="hover:underline">
                                                {{ $article->title }}
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="aspect-[3/4] flex items-center justify-center text-gray-400">
                                    No hay noticias en esta categoría.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Botones de Navegación --}}
                <button x-ref="prev"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 p-2 bg-white/70 rounded-full shadow-md hover:bg-white transition ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-gray-800">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
                <button x-ref="next"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 p-2 bg-white/70 rounded-full shadow-md hover:bg-white transition mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-gray-800">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        </section>
    @endif
</div>
