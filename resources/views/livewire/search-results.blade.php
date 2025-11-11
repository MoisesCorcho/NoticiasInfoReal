<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-white mb-6 border-b border-white/10 pb-4">
        Resultados para: <span class="text-[#d71935]">"{{ $query }}"</span>
    </h1>

    @if($query)
        <div class="space-y-8">
            @forelse($articles as $article)
                <x-articles.article-horizontal-card :article="$article" />
            @empty
                <div class="text-center py-12 bg-[#18181C] rounded-lg border border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto text-gray-700 mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <p class="text-xl text-gray-400">No encontramos noticias que coincidan con tu búsqueda.</p>
                    <p class="text-gray-500 mt-2">Intenta con otros términos.</p>
                </div>
            @endforelse

            @if($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    @else
        <p class="text-gray-400">Por favor, ingresa un término para buscar.</p>
    @endif
</div>