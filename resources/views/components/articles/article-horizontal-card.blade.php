@props([
    'article',
    'showExcerpt' => true,
])

@php
    $imageUrl = $article->featured_image_url
        ? \Illuminate\Support\Facades\Storage::url($article->featured_image_url)
        : null;
@endphp

<article {{ $attributes->merge(['class' => 'flex flex-col md:flex-row gap-6 bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition']) }}>
    @if($imageUrl)
        <a href="{{ route('article.show', $article->slug) }}" class="shrink-0 md:w-1/3">
            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-full h-48 md:h-full object-cover rounded-lg">
        </a>
    @endif
    <div class="flex-1">
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                {{ $article->category->name ?? 'General' }}
            </span>
            @if($article->published_at)
                <span class="text-gray-500 text-xs">{{ $article->published_at->format('d M, Y') }}</span>
            @endif
        </div>
        <h2 class="text-xl font-bold mb-2 leading-tight">
            <a href="{{ route('article.show', $article->slug) }}" class="hover:text-red-700">
                {{ $article->title }}
            </a>
        </h2>
        @if($showExcerpt)
            <p class="text-gray-600 mb-4 line-clamp-3">{{ $article->excerpt }}</p>
        @endif
        <a href="{{ route('article.show', $article->slug) }}" class="text-red-600 font-semibold text-sm hover:underline">
            Leer más &rarr;
        </a>
    </div>
</article>

