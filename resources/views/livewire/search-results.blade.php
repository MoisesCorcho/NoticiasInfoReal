<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-4">
        Resultados para: <span class="text-red-600">"{{ $query }}"</span>
    </h1>

    @if($query)
        <div class="space-y-8">
            @forelse($articles as $article)
                <x-article-horizontal-card :article="$article" />
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