<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 mb-6 border-b border-white/10 [html[data-theme=light]_&]:border-gray-200 pb-4 transition-colors duration-200">
        Resultados para: <span class="text-red-primary">"{{ $query }}"</span>
    </h1>

    @if($query)
        <div class="space-y-8">
            @forelse($articles as $article)
                <x-articles.article-horizontal-card :article="$article" />
            @empty
                <div class="text-center py-12 bg-[#18181C] [html[data-theme=light]_&]:bg-white rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-gray-700 [html[data-theme=light]_&]:text-gray-400 mb-4 transition-colors duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <p class="text-xl text-gray-400 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">No encontramos noticias que coincidan con tu búsqueda.</p>
                    <p class="text-gray-500 [html[data-theme=light]_&]:text-gray-600 mt-2 transition-colors duration-200">Intenta con otros términos.</p>
                </div>
            @endforelse

            @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    @else
        <p class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">Por favor, ingresa un término para buscar.</p>
    @endif
</div>