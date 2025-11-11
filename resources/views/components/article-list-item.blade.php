@props([
    'article',
    'meta' => null,
    'imageSize' => 'w-24 h-24',
    'titleClass' => 'font-bold text-md hover:text-red-700 leading-tight',
    'linkClass' => '',
])

@php
    $imageUrl = $article->featured_image_url
        ? \Illuminate\Support\Facades\Storage::url($article->featured_image_url)
        : null;
@endphp

<div {{ $attributes->merge(['class' => 'flex gap-4 items-start']) }}>
    @if($imageUrl)
        <a href="{{ route('article.show', $article->slug) }}" class="shrink-0">
            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" class="{{ $imageSize }} object-cover rounded">
        </a>
    @endif
    <div>
        <h4 class="{{ $titleClass }}">
            <a href="{{ route('article.show', $article->slug) }}" class="{{ $linkClass }}">
                {{ $article->title }}
            </a>
        </h4>
        @if($meta)
            <p class="text-xs text-gray-500 mt-1">
                {{ $meta }}
            </p>
        @endif
    </div>
</div>

