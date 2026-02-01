<div>
    {{-- Breadcrumb fuera del contenido principal --}}
    <div class="mb-4">
        <x-ui.category-breadcrumb :category="$article->category" class="text-xs text-gray-400" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    {{-- COLUMNA PRINCIPAL: CONTENIDO DEL ARTÍCULO --}}
    <div class="lg:col-span-2">
        <article class="bg-[#18181C] [html[data-theme=light]_&]:bg-white p-6 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 mb-8 transition-colors duration-200">
            {{-- Encabezado --}}
            <header class="mb-6">
                {{-- Categorías --}}
                <div class="flex gap-2 mb-4">
                    <span class="bg-red-primary text-white text-xs font-bold px-2 py-1 rounded uppercase">
                        {{ $article->category->name }}
                    </span>
                </div>

                {{-- Título --}}
                <h1 class="text-3xl md:text-4xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 leading-tight mb-4 transition-colors duration-200">
                    {{ $article->title }}
                </h1>

                {{-- Metadatos --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-gray-400 [html[data-theme=light]_&]:text-gray-600 text-sm border-b border-white/10 [html[data-theme=light]_&]:border-gray-200 pb-4 mb-6 transition-colors duration-200">
                    <div class="flex flex-wrap items-center gap-4">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            {{ $article->published_at->translatedFormat('d F, Y') }}
                        </span>
                        <span class="flex items-center">
                            @if($article->author->image)
                                <img src="{{ Storage::url($article->author->image) }}" alt="{{ $article->author->name }}" class="w-8 h-8 md:w-10 md:h-10 rounded-full mr-2 object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            @endif
                            {{ $article->author->name }}
                        </span>
                    </div>
                    {{-- Botones de compartir: parte superior --}}
                    <x-ui.share-buttons :article="$article" />
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
            <div
                x-data="{
                    init() {
                        // Regex único para: Extensiones solas O Extensiones + Peso
                        const badCaption = /\.(jpg|jpeg|png|gif|webp|bmp|svg)(\s+\d+(\.\d+)?\s*[KMGTP]?B)?$/i;

                        this.$el.querySelectorAll('figure').forEach(fig => {
                            const cap = fig.querySelector('figcaption');
                            if (!cap) return;

                            const text = cap.innerText.trim();
                            const srcName = fig.querySelector('img')?.src?.split('/').pop()?.toLowerCase();

                            // Eliminar si: Vacío OR Coincide Regex OR Coincide nombre de archivo imagen
                            if (!text || badCaption.test(text) || (srcName && text.toLowerCase() === decodeURIComponent(srcName))) {
                                cap.remove();
                            }
                        });
                    }
                }"
                class="prose prose-lg max-w-none [html[data-theme=dark]_&]:prose-invert prose-img:rounded-lg prose-headings:font-bold prose-headings:[html[data-theme=light]_&]:text-gray-900 prose-a:text-red-primary hover:prose-a:text-red-700 [html[data-theme=light]_&]:prose-p:text-gray-700 transition-colors duration-200">
                {!! $article->content !!}
            </div>

            {{-- Tags (si los hay) --}}
            @if($article->tags->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
                    <h3 class="text-sm font-bold text-gray-300 [html[data-theme=light]_&]:text-gray-700 uppercase mb-3 transition-colors duration-200">Etiquetas:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <span class="bg-[#333233] [html[data-theme=light]_&]:bg-gray-200 text-gray-300 [html[data-theme=light]_&]:text-gray-700 text-xs px-3 py-1.5 rounded-full transition-colors duration-200">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Botones de compartir: parte inferior --}}
            <div class="mt-8 pt-6 border-t border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">

                <div class="flex justify-center">
                    <x-ui.share-buttons :article="$article" />
                </div>

            </div>
        </article>

        <nav class="flex justify-between items-stretch mb-8 gap-4">
            {{-- Artículo Anterior --}}
            <div class="w-1/2">
                @if($previousArticle)
                    <a href="{{ route('article.show', $previousArticle->slug) }}" class="flex items-center bg-[#18181C] [html[data-theme=light]_&]:bg-white p-4 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 hover:bg-[#333233] [html[data-theme=light]_&]:hover:bg-gray-100 transition-colors h-full group">
                        @if($previousArticle->featured_image_url)
                            <img src="{{ Storage::url($previousArticle->featured_image_url) }}" alt="{{ $previousArticle->title }}" class="w-20 h-20 object-cover rounded-md mr-4 shrink-0 hidden sm:block">
                        @endif
                        <div>
                            <span class="block text-xs text-gray-400 [html[data-theme=light]_&]:text-gray-600 uppercase mb-1 group-hover:text-red-primary [html[data-theme=light]_&]:group-hover:text-red-primary transition-colors">&larr; Anterior</span>
                            <h4 class="font-bold text-white [html[data-theme=light]_&]:text-gray-900 leading-tight line-clamp-2 group-hover:text-red-primary [html[data-theme=light]_&]:group-hover:text-red-primary transition-colors">
                                {{ $previousArticle->title }}
                            </h4>
                        </div>
                    </a>
                @endif
            </div>

            {{-- Artículo Siguiente --}}
            <div class="w-1/2">
                @if($nextArticle)
                    <a href="{{ route('article.show', $nextArticle->slug) }}" class="flex items-center justify-end text-right bg-[#18181C] [html[data-theme=light]_&]:bg-white p-4 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 hover:bg-[#333233] [html[data-theme=light]_&]:hover:bg-gray-100 transition-colors h-full group">
                        <div>
                            <span class="block text-xs text-gray-400 [html[data-theme=light]_&]:text-gray-600 uppercase mb-1 group-hover:text-red-primary [html[data-theme=light]_&]:group-hover:text-red-primary transition-colors">Siguiente &rarr;</span>
                            <h4 class="font-bold text-white [html[data-theme=light]_&]:text-gray-900 leading-tight line-clamp-2 group-hover:text-red-primary [html[data-theme=light]_&]:group-hover:text-red-primary transition-colors">
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

        <div class="bg-[#18181C] [html[data-theme=light]_&]:bg-white p-6 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
             <h4 class="text-lg font-bold text-white [html[data-theme=light]_&]:text-gray-900 mb-6 uppercase border-b-2 border-red-primary pb-2 inline-block transition-colors duration-200">Entradas Recientes</h4>
             <div class="space-y-4">
                 @foreach($recentArticles as $recent)
                     <x-articles.article-list-item
                         :article="$recent"
                         :meta="$recent->published_at?->format('d M, Y')"
                         image-size="w-20 h-20"
                         title-class="font-bold text-sm text-white [html[data-theme=light]_&]:text-gray-900 leading-tight hover:text-red-primary [html[data-theme=light]_&]:hover:text-red-primary transition-colors duration-200"
                         class="items-start gap-3"
                     />
                 @endforeach
             </div>
        </div>
    </aside>
    </div>
</div>
