<div class="space-y-12">
    {{-- SECCIÓN 1: HERO (Noticia principal grande + 2 laterales) --}}
    @if($heroArticles->isNotEmpty())
        <section id="hero" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Noticia Principal (ocupa 2 columnas en pantallas grandes) --}}
            <div class="lg:col-span-2 group relative h-96 overflow-hidden rounded-lg shadow-lg">
                @if($heroArticles[0]->featured_image_url)
                    <img src="{{ Storage::url($heroArticles[0]->featured_image_url) }}" alt="{{ $heroArticles[0]->title }}"
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
                @foreach($heroArticles->skip(1) as $article)
                    <div class="relative h-[184px] overflow-hidden rounded-lg shadow group">
                        @if($article->featured_image_url)
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
            @foreach($latestArticles as $article)
                <article class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition">
                    @if($article->featured_image_url)
                        <a href="{{ route('article.show', $article->slug) }}">
                            <img src="{{ Storage::url($article->featured_image_url) }}" class="w-full h-48 object-cover">
                        </a>
                    @endif
                    <div class="p-4">
                        <div class="flex items-center text-xs text-gray-500 mb-2">
                            <span class="text-red-600 font-semibold mr-2">{{ $article->category->name ?? 'General' }}</span>
                            <span>&bull; {{ $article->published_at->format('d M, Y') }}</span>
                        </div>
                        <h3 class="font-bold text-lg leading-snug mb-2">
                            <a href="{{ route('article.show', $article->slug) }}" class="hover:text-red-700">
                                {{ $article->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-3">{{ $article->excerpt }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- SECCIÓN 3: CATEGORÍAS ESPECÍFICAS (Dos columnas) --}}
    <section id="category-sections" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Columna Deportes --}}
        <div>
            <div class="flex items-center justify-between mb-6 border-b-2 border-red-600">
                <h2 class="text-xl font-bold uppercase bg-red-600 text-white px-4 py-1 inline-block">
                    Deportes
                </h2>
            </div>
            <div class="space-y-6">
                @forelse($sportsArticles as $article)
                    <div class="flex gap-4 items-start border-b border-gray-100 pb-4">
                        @if($article->featured_image_url)
                            <a href="{{ route('article.show', $article->slug) }}" class="shrink-0">
                                <img src="{{ Storage::url($article->featured_image_url) }}" class="w-24 h-24 object-cover rounded">
                            </a>
                        @endif
                        <div>
                            <h4 class="font-bold text-md hover:text-red-700 leading-tight">
                                <a href="{{ route('article.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h4>
                             <p class="text-xs text-gray-500 mt-1">{{ $article->published_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No hay noticias de deportes aún.</p>
                @endforelse
            </div>
        </div>

        {{-- Columna Región --}}
        <div>
            <div class="flex items-center justify-between mb-6 border-b-2 border-blue-600">
                <h2 class="text-xl font-bold uppercase bg-blue-600 text-white px-4 py-1 inline-block">
                    Región
                </h2>
            </div>
            <div class="space-y-6">
                 @forelse($regionArticles as $article)
                    <div class="flex gap-4 items-start border-b border-gray-100 pb-4">
                        @if($article->featured_image_url)
                            <a href="{{ route('article.show', $article->slug) }}" class="shrink-0">
                                <img src="{{ Storage::url($article->featured_image_url) }}" class="w-24 h-24 object-cover rounded">
                            </a>
                        @endif
                        <div>
                            <h4 class="font-bold text-md hover:text-blue-700 leading-tight">
                                <a href="{{ route('article.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $article->published_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No hay noticias regionales aún.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>