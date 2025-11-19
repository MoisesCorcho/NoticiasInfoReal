@props([
    'article',
    'meta' => null,
    'imageSize' => 'w-24 h-24',
    'titleClass' => 'font-bold text-md text-white [html[data-theme=light]_&]:text-gray-900 hover:text-[#d71935] leading-tight transition-colors duration-200',
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
            <img src="{{ $imageUrl }}" 
                 alt="{{ $article->title }}" 
                 onerror="this.src='https://placehold.co/100x100/18181C/333233?text=...'"
                 class="{{ $imageSize }} object-cover rounded">
        </a>
    @endif
    <div>
        <h4 class="{{ $titleClass }}">
            <a href="{{ route('article.show', $article->slug) }}" class="{{ $linkClass }}">
                {{ $article->title }}
            </a>
        </h4>
        @if($meta)
            <p class="text-xs text-gray-500 [html[data-theme=light]_&]:text-gray-600 mt-1 transition-colors duration-200">
                {{ $meta }}
            </p>
        @endif
    </div>
</div>