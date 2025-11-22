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

<article {{ $attributes->merge(['class' => 'bg-[#18181C] [html[data-theme=light]_&]:bg-white rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 overflow-hidden flex flex-col group transition-colors hover:bg-[#333233] [html[data-theme=light]_&]:hover:bg-gray-100']) }}>
    @if($imageUrl)
        <a href="{{ route('article.show', $article->slug) }}" class="block">
            <div class="relative {{ $imageHeight }} w-full overflow-hidden">
                <img src="{{ $imageUrl }}" 
                     alt="{{ $article->title }}" 
                     onerror="this.src='https://placehold.co/400x300/18181C/333233?text=Art%C3%ADculo'"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
            </div>
        </a>
    @else
        {{-- Fallback si no hay imagen --}}
        <a href="{{ route('article.show', $article->slug) }}" class="block">
            <div class="relative {{ $imageHeight }} w-full overflow-hidden bg-[#333233] [html[data-theme=light]_&]:bg-gray-100 flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">
                <span>Sin Imagen</span>
            </div>
        </a>
    @endif

    <div class="p-4 flex flex-col flex-grow">
        <div class="flex items-center text-xs text-gray-500 [html[data-theme=light]_&]:text-gray-600 mb-2 transition-colors duration-200">
            <span class="text-red-primary font-semibold mr-2">
                {{ $article->category->name ?? 'General' }}
            </span>
            @if($article->published_at)
                <span>&bull; {{ $article->published_at->translatedFormat('d M, Y') }}</span>
            @endif
        </div>
        <h3 class="font-bold text-lg text-white [html[data-theme=light]_&]:text-gray-900 leading-snug mb-2 flex-grow transition-colors duration-200">
            <a href="{{ route('article.show', $article->slug) }}" class="hover:text-red-primary">
                {{ $article->title }}
            </a>
        </h3>
        @if($showExcerpt)
            <p class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 text-sm line-clamp-3 transition-colors duration-200">
                {{ $article->excerpt }}
            </p>
        @endif
    </div>
</article>