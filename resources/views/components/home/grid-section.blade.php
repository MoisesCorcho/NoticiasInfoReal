@props(['section', 'articles'])

{{-- Props: $section (el objeto HomepageSection) y $articles (la colección de artículos) --}}
{{-- Este componente es para el layout 'grid', ¡idéntico a la imagen "Sports"! --}}
@php
    $title = $section->display_title ?? $section->category->name;
    // Si la categoría no está seteada (como en "Últimas Noticias"), usamos el slug del objeto
    $categorySlug = $section->category->slug ?? 'ultimas';
@endphp

<section>
    {{-- Título de la sección (Estilo "Sports" con línea) --}}
    <div class="flex items-center mb-6">
        {{-- Línea izquierda --}}
        <div class="flex-grow bg-[#d71935] h-2"></div>

        {{-- Título --}}
        <h2 class="text-lg font-bold uppercase text-white mx-4 flex-shrink-0">
            {{ $title }}
        </h2>

        {{-- Línea derecha --}}
        <div class="flex-grow bg-[#d71935] h-2"></div>
    </div>

    @if ($articles->isNotEmpty())
        {{-- Esta es la rejilla 3x2 (en desktop) que se ve en la imagen --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($articles as $article)
                {{-- Esta es la tarjeta individual de cada artículo en la rejilla --}}
                <div class="group relative">
                    <a href="{{ route('article.show', $article->slug) }}" class="block">
                        <div class="relative aspect-[4/3] w-full overflow-hidden rounded-lg">
                            <img src="{{ $article->featured_image_url ? Storage::url($article->featured_image_url) : 'https://placehold.co/400x300/18181C/333233?text=Art%C3%ADculo' }}"
                                 alt="{{ $article->title }}"
                                 onerror="this.src='https://placehold.co/400x300/18181C/333233?text=Error'"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            
                            <span class="absolute top-3 left-3 inline-block bg-[#d71935] text-white text-[10px] font-bold px-1.5 py-0.5 rounded z-10">
                                {{ $article->category->name }}
                            </span>
                        </div>
                    </a>
                    <div class="mt-3">
                        <h3 class="text-lg font-bold text-white leading-tight group-hover:text-[#d71935] transition-colors duration-300 line-clamp-2">
                            <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                        </h3>
                        <span class="text-gray-500 text-xs mt-1">{{ $article->published_at->translatedFormat('j M, Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">No hay artículos disponibles para "{{ $title }}".</p>
    @endif
</section>