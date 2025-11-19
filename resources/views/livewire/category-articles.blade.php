<div class="max-w-screen-xl mx-auto">
    <header class="mb-12 border-b-2 border-[#d71935] pb-4">
        <x-ui.category-breadcrumb :category="$category" class="text-sm text-gray-400 mb-2" />
        <h1 class="text-4xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 uppercase transition-colors duration-200">
            {{ $category->name }}
        </h1>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($articles as $article)
            <article class="bg-[#18181C] [html[data-theme=light]_&]:bg-white rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors hover:bg-[#333233] [html[data-theme=light]_&]:hover:bg-gray-100 overflow-hidden flex flex-col h-full">
                @if($article->featured_image_url)
                    <a href="{{ route('article.show', $article->slug) }}" class="aspect-video block overflow-hidden">
                        <img src="{{ Storage::url($article->featured_image_url) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    </a>
                @endif
                <div class="p-5 flex-1 flex flex-col">
                    <div class="text-xs text-gray-400 [html[data-theme=light]_&]:text-gray-600 mb-2 transition-colors duration-200">
                        {{ $article->published_at->format('d M, Y') }}
                    </div>
                    <h2 class="text-xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 leading-tight mb-3 flex-1 transition-colors duration-200">
                        <a href="{{ route('article.show', $article->slug) }}" class="hover:text-[#d71935] transition-colors">
                            {{ $article->title }}
                        </a>
                    </h2>
                    <p class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 text-sm line-clamp-3 mb-4 transition-colors duration-200">
                        {{ $article->excerpt }}
                    </p>
                    <div class="mt-auto pt-4 border-t border-white/10 [html[data-theme=light]_&]:border-gray-200 text-sm text-gray-500 [html[data-theme=light]_&]:text-gray-600 flex items-center transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5 text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">
                          <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                        </svg>
                        {{ $article->author->name }}
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">
                No hay noticias en esta categoría todavía.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-12">
        {{ $articles->links() }}
    </div>
</div>