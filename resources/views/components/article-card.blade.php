@props([
    'article',
    'showExcerpt' => true,
    'imageHeight' => 'h-48',
])

@php
    $imageUrl = $article->featured_image_url
        ? \Illuminate\Support\Facades\Storage::url($article->featured_image_url)
        : null;
@endphp

<article {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition']) }}>
    @if($imageUrl)
        <a href="{{ route('article.show', $article->slug) }}">
            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="w-full {{ $imageHeight }} object-cover">
        </a>
    @endif
    <div class="p-4">
        <div class="flex items-center text-xs text-gray-500 mb-2">
            <span class="text-red-600 font-semibold mr-2">
                {{ $article->category->name ?? 'General' }}
            </span>
            @if($article->published_at)
                <span>&bull; {{ $article->published_at->format('d M, Y') }}</span>
            @endif
        </div>
        <h3 class="font-bold text-lg leading-snug mb-2">
            <a href="{{ route('article.show', $article->slug) }}" class="hover:text-red-700">
                {{ $article->title }}
            </a>
        </h3>
        @if($showExcerpt)
            <p class="text-gray-600 text-sm line-clamp-3">
                {{ $article->excerpt }}
            </p>
        @endif
    </div>
</article>

