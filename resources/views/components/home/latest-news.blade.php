@props(['articles'])

<section id="latest-news">
    <div class="flex items-center justify-between mb-6 border-b border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
        <h2 class="text-xl font-bold uppercase text-white [html[data-theme=light]_&]:text-gray-900 relative inline-block py-2 transition-colors duration-200">
            Últimas Noticias
            <span class="absolute bottom-0 left-0 w-1/2 h-1 bg-[#d71935]"></span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($articles as $article)
            <div class="bg-[#18181C] [html[data-theme=light]_&]:bg-white rounded-lg overflow-hidden group border border-white/10 [html[data-theme=light]_&]:border-gray-200 flex flex-col transition-colors duration-200">
                <a href="{{ route('article.show', $article->slug) }}" class="block">
                    <div class="relative h-48 w-full overflow-hidden">
                        @if ($article->featured_image_url)
                            <img
                                src="{{ Storage::url($article->featured_image_url) }}"
                                alt="{{ $article->title }}"
                                onerror="this.src='https://placehold.co/400x300/18181C/333233?text=Art%C3%ADculo'"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            >
                        @else
                            <div class="absolute inset-0 h-full w-full bg-[#333233] [html[data-theme=light]_&]:bg-gray-100 flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">
                                <span>Sin Imagen</span>
                            </div>
                        @endif
                    </div>
                </a>
                <div class="p-4 flex flex-col flex-grow">
                    <span class="text-xs uppercase tracking-widest text-[#d71935] font-bold">
                        {{ $article->category->name ?? 'General' }}
                    </span>
                    <h3 class="text-lg font-bold text-white [html[data-theme=light]_&]:text-gray-900 mt-2 leading-snug transition-colors duration-200">
                        <a href="{{ route('article.show', $article->slug) }}" class="hover:underline">
                            {{ $article->title }}
                        </a>
                    </h3>
                    <p class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 text-sm mt-2 line-clamp-3 flex-grow transition-colors duration-200">
                        {{ $article->excerpt }}
                    </p>
                    <span class="text-xs text-gray-500 [html[data-theme=light]_&]:text-gray-600 mt-4 transition-colors duration-200">
                        {{ $article->published_at->translatedFormat('M d, Y') }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</section>