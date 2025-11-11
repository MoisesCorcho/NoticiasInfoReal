<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    {{-- COLUMNA PRINCIPAL: CONTENIDO DEL ARTÍCULO --}}
    <div class="lg:col-span-2">
        <article class="bg-[#18181C] p-6 rounded-lg border border-white/10 mb-8">
            {{-- Encabezado --}}
            <header class="mb-6">
                {{-- Categorías --}}
                <div class="flex gap-2 mb-4">
                    <a href="#" class="bg-[#d71935] text-white text-xs font-bold px-2 py-1 rounded uppercase hover:bg-red-700 transition">
                        {{ $article->category->name }}
                    </a>
                </div>

                {{-- Título --}}
                <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                    {{ $article->title }}
                </h1>

                {{-- Metadatos --}}
                <div class="flex items-center text-gray-400 text-sm space-x-4 border-b border-white/10 pb-4 mb-6">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        {{ $article->published_at->translatedFormat('d F, Y') }}
                    </span>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        {{ $article->author->name }}
                    </span>
                    {{-- Contador de comentarios si quieres --}}
                    <span class="flex items-center">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                         </svg>
                        {{ $article->comments->count() }}
                    </span>
                </div>
            </header>

            {{-- Imagen Destacada --}}
            @if($article->featured_image_url)
                <figure class="mb-8">
                    <img src="{{ Storage::url($article->featured_image_url) }}" alt="{{ $article->title }}" class="w-full h-auto rounded-lg">
                    {{-- <figcaption class="text-sm text-gray-500 mt-2 italic">Pie de foto opcional</figcaption> --}}
                </figure>
            @endif

            {{-- Cuerpo del Artículo --}}
            <div class="prose prose-lg max-w-none prose-invert prose-img:rounded-lg prose-headings:font-bold prose-a:text-[#d71935] hover:prose-a:text-red-700">
                {!! $article->content !!}
            </div>

            {{-- Tags (si los hay) --}}
            @if($article->tags->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-white/10">
                    <h3 class="text-sm font-bold text-gray-300 uppercase mb-3">Etiquetas:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <a href="#" class="bg-[#333233] text-gray-300 text-xs px-3 py-1.5 rounded-full hover:bg-[#4a494a] transition">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </article>

        <nav class="flex justify-between items-stretch mb-8 gap-4">
            {{-- Artículo Anterior --}}
            <div class="w-1/2">
                @if($previousArticle)
                    <a href="{{ route('article.show', $previousArticle->slug) }}" class="flex items-center bg-[#18181C] p-4 rounded-lg border border-white/10 hover:bg-[#333233] transition h-full group">
                        @if($previousArticle->featured_image_url)
                            <img src="{{ Storage::url($previousArticle->featured_image_url) }}" alt="{{ $previousArticle->title }}" class="w-20 h-20 object-cover rounded-md mr-4 shrink-0 hidden sm:block">
                        @endif
                        <div>
                            <span class="block text-xs text-gray-400 uppercase mb-1 group-hover:text-[#d71935] transition-colors">&larr; Anterior</span>
                            <h4 class="font-bold text-white leading-tight line-clamp-2 group-hover:text-[#d71935] transition-colors">
                                {{ $previousArticle->title }}
                            </h4>
                        </div>
                    </a>
                @endif
            </div>

            {{-- Artículo Siguiente --}}
            <div class="w-1/2">
                @if($nextArticle)
                    <a href="{{ route('article.show', $nextArticle->slug) }}" class="flex items-center justify-end text-right bg-[#18181C] p-4 rounded-lg border border-white/10 hover:bg-[#333233] transition h-full group">
                        <div>
                            <span class="block text-xs text-gray-400 uppercase mb-1 group-hover:text-[#d71935] transition-colors">Siguiente &rarr;</span>
                            <h4 class="font-bold text-white leading-tight line-clamp-2 group-hover:text-[#d71935] transition-colors">
                                {{ $nextArticle->title }}
                            </h4>
                        </div>
                        @if($nextArticle->featured_image_url)
                            <img src="{{ Storage::url($nextArticle->featured_image_url) }}" alt="{{ $nextArticle->title }}" class="w-20 h-20 object-cover rounded-md ml-4 shrink-0 hidden sm:block">
                        @endif
                    </a>
                @endif
            </div>
        </nav>

        {{-- Sección de Comentarios --}}
        @if($article->allows_comments)
            <livewire:article-comments :article="$article" />
        @endif
    </div>

    {{-- BARRA LATERAL (SIDEBAR) --}}
    <aside class="space-y-8">
        {{-- Widget: Buscador --}}
        <livewire:search-widget />

        <div class="bg-[#18181C] p-6 rounded-lg border border-white/10">
             <h4 class="text-lg font-bold text-white mb-6 uppercase border-b-2 border-[#d71935] pb-2 inline-block">Entradas Recientes</h4>
             <div class="space-y-4">
                 @foreach($recentArticles as $recent)
                     <x-articles.article-list-item
                         :article="$recent"
                         :meta="$recent->published_at?->format('d M, Y')"
                         image-size="w-20 h-20"
                         title-class="font-bold text-sm text-white leading-tight hover:text-[#d71935]"
                         class="items-start gap-3"
                     />
                 @endforeach
             </div>
        </div>

        <div class="bg-[#18181C] p-6 rounded-lg border-2 border-dashed border-white/10 text-center text-gray-500">
            ESPACIO PUBLICITARIO<br>(300x250)
        </div>
    </aside>
</div>