<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">
        Resultados para: <span class="text-red-600">"{{ $query }}"</span>
    </h1>

    @if($query)
        <div class="space-y-8">
            @forelse($articles as $article)
                <article class="flex flex-col md:flex-row gap-6 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition">
                    @if($article->featured_image_url)
                        <a href="{{ route('article.show', $article->slug) }}" class="shrink-0 md:w-1/3">
                             <img src="{{ Storage::url($article->featured_image_url) }}" class="w-full h-48 md:h-full object-cover rounded-lg">
                        </a>
                    @endif
                    <div class="flex-1">
                         <div class="flex items-center gap-2 mb-2">
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $article->category->name }}
                            </span>
                            <span class="text-gray-500 text-xs">{{ $article->published_at->format('d M, Y') }}</span>
                        </div>
                        <h2 class="text-xl font-bold mb-2 leading-tight">
                            <a href="{{ route('article.show', $article->slug) }}" class="hover:text-red-700">
                                {{ $article->title }}
                            </a>
                        </h2>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                         <a href="{{ route('article.show', $article->slug) }}" class="text-red-600 font-semibold text-sm hover:underline">
                            Leer más &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-gray-300 mb-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <p class="text-xl text-gray-500">No encontramos noticias que coincidan con tu búsqueda.</p>
                    <p class="text-gray-400 mt-2">Intenta con otros términos.</p>
                </div>
            @endforelse

            @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    @else
        <p class="text-gray-500">Por favor, ingresa un término para buscar.</p>
    @endif
</div>