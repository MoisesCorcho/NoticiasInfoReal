@props(['articles'])

<section id="latest-news">
    <div class="flex items-center justify-between mb-6 border-b border-gray-200">
        <h2 class="text-xl font-bold uppercase text-gray-800 relative inline-block py-2">
            Últimas Noticias
            <span class="absolute bottom-0 left-0 w-1/2 h-1 bg-red-600"></span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($articles as $article)
            <x-articles.article-card :article="$article" />
        @endforeach
    </div>
</section>
