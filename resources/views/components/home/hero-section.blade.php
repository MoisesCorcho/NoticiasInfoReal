@props(['articles'])

<section id="hero" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Noticia Principal (ocupa 2 columnas en pantallas grandes) --}}
    <div class="lg:col-span-2 group relative h-96 overflow-hidden rounded-lg border border-white/10">
        @if ($articles[0]->featured_image_url)
            <img src="{{ Storage::url($articles[0]->featured_image_url) }}" alt="{{ $articles[0]->title }}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="w-full h-full bg-[#18181C] items-center justify-center text-gray-500 hidden">Sin Imagen</div>
        @else
            <div class="w-full h-full bg-[#18181C] flex items-center justify-center text-gray-500">Sin Imagen</div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
            <div class="absolute bottom-0 p-6 text-white">
                <span class="inline-block bg-[#d71935] text-xs font-bold px-2 py-1 mb-2 rounded">
                    {{ $articles[0]->category->name ?? 'General' }}
                </span>
                <h1 class="text-3xl font-bold leading-tight hover:underline">
                    <a href="{{ route('article.show', $articles[0]->slug) }}">
                        {{ $articles[0]->title }}
                    </a>
                </h1>
                <p class="text-gray-300 mt-2 text-sm line-clamp-2">{{ $articles[0]->excerpt }}</p>
                <p class="text-gray-400 text-xs mt-3">{{ $articles[0]->published_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    {{-- Noticias Secundarias del Hero (columna derecha) --}}
    <div class="space-y-6">
        @foreach ($articles->skip(1) as $article)
            <div class="relative h-[184px] overflow-hidden rounded-lg group border border-white/10">
                @if ($article->featured_image_url)
                    <img src="{{ Storage::url($article->featured_image_url) }}" alt="{{ $article->title }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="w-full h-full bg-[#18181C] items-center justify-center text-gray-500 hidden"></div>
                @else
                    {{-- Fallback para imágenes secundarias --}}
                    <div class="w-full h-full bg-[#18181C] flex items-center justify-center text-gray-500"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                    <div class="absolute bottom-0 p-4 text-white">
                        <span class="bg-[#d71935] text-[10px] font-bold px-1.5 py-0.5 rounded">
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