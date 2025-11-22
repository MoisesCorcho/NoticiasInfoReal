@props(['section', 'articles'])

{{-- Props: $section (el objeto HomepageSection) y $articles (la colección de artículos) --}}
@php
    $title = $section->display_title ?? $section->category->name;
    $mainArticle = $articles->first();
    $asideArticles = $articles->skip(1)->take(4); // Tomamos los siguientes 4
@endphp

<section>
    {{-- Título de la sección (Estilo "Sports" con línea) --}}
    <div class="flex items-center mb-6">
        {{-- Línea izquierda --}}
        <div class="flex-grow bg-[#d71935] h-0.5"></div>

        {{-- Título --}}
        <h2 class="text-lg font-bold uppercase text-white [html[data-theme=light]_&]:text-gray-900 mx-4 flex-shrink-0 transition-colors duration-200">
            {{ $title }}
        </h2>

        {{-- Línea derecha --}}
        <div class="flex-grow bg-[#d71935] h-0.5"></div>
    </div>

    @if ($mainArticle)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Columna Izquierda: Artículo Principal (como "Politics") --}}
            <div class="group relative">
                <a href="{{ route('article.show', $mainArticle->slug) }}" class="block">
                    <div class="relative aspect-video w-full overflow-hidden rounded-lg">
                        <img src="{{ $mainArticle->featured_image_url ? Storage::url($mainArticle->featured_image_url) : 'https://placehold.co/600x400/18181C/333233?text=Art%C3%ADculo' }}"
                             alt="{{ $mainArticle->title }}"
                             onerror="this.src='https://placehold.co/600x400/18181C/333233?text=Error'"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        {{-- Categoría sobre imagen --}}
                        <span class="absolute top-3 left-3 inline-block bg-[#d71935] text-white text-[10px] font-bold px-1.5 py-0.5 rounded z-10">
                            {{ $mainArticle->category->name }}
                        </span>
                    </div>
                </a>
                <div class="mt-4">
                    <h3 class="text-xl md:text-2xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 leading-tight group-hover:text-[#d71935] transition-colors duration-300">
                        <a href="{{ route('article.show', $mainArticle->slug) }}">{{ $mainArticle->title }}</a>
                    </h3>
                    <p class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 text-sm mt-2 line-clamp-3 transition-colors duration-200">
                        {{ $mainArticle->excerpt ?? Str::limit(strip_tags($mainArticle->content), 150) }}
                    </p>
                    <div class="text-gray-500 [html[data-theme=light]_&]:text-gray-600 text-xs mt-3 transition-colors duration-200">
                        <span>{{ $mainArticle->published_at->translatedFormat('j M, Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Columna Derecha: Lista de Artículos (como "Science", pero más) --}}
            <div class="flex flex-col space-y-5">
                @forelse ($asideArticles as $article)
                    <div class="group flex items-center gap-4">
                        <a href="{{ route('article.show', $article->slug) }}" class="block w-24 h-20 flex-shrink-0">
                            <img src="{{ $article->featured_image_url ? Storage::url($article->featured_image_url) : 'https://placehold.co/100x80/18181C/333233?text=Art%C3%ADculo' }}"
                                 alt="{{ $article->title }}"
                                 onerror="this.src='https://placehold.co/100x80/18181C/333233?text=Error'"
                                 class="w-full h-full object-cover rounded-md transition-opacity duration-300 group-hover:opacity-80">
                        </a>
                        <div>
                            <span class="inline-block text-[#d71935] text-[10px] font-bold mb-1">
                                {{ $article->category->name }}
                            </span>
                            <h4 class="text-md font-semibold text-white [html[data-theme=light]_&]:text-gray-900 leading-tight group-hover:text-[#d71935] transition-colors duration-300 line-clamp-2">
                                <a href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                            </h4>
                            <span class="text-gray-500 [html[data-theme=light]_&]:text-gray-600 text-xs mt-1 transition-colors duration-200">{{ $article->published_at->translatedFormat('j M, Y') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">No hay artículos adicionales en esta sección.</p>
                @endforelse
            </div>
        </div>
    @else
        <p class="text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">No hay artículos disponibles para "{{ $title }}".</p>
    @endif
</section>