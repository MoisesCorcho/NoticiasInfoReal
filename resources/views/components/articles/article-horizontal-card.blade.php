@props([
    'article',
    'showExcerpt' => true,
])

@php
    $imageUrl = $article->featured_image_url
        ? \Illuminate\Support\Facades\Storage::url($article->featured_image_url)
        : null;
@endphp

<article {{ $attributes->merge(['class' => 'flex flex-col md:flex-row gap-6 bg-[#18181C] [html[data-theme=light]_&]:bg-white p-4 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors hover:bg-[#333233] [html[data-theme=light]_&]:hover:bg-gray-100']) }}>
    @if($imageUrl)
        <a href="{{ route('article.show', $article->slug) }}" class="shrink-0 md:w-1/3">
            <img src="{{ $imageUrl }}" 
                 alt="{{ $article->title }}" 
                 onerror="this.src='https://placehold.co/400x400/18181C/333233?text=Art%C3%ADculo'"
                 class="w-full h-48 md:h-full object-cover rounded-lg">
        </a>
    @else
        <a href="{{ route('article.show', $article->slug) }}" class="shrink-0 md:w-1/3">
             <div class="w-full h-48 md:h-full object-cover rounded-lg bg-[#333233] [html[data-theme=light]_&]:bg-gray-100 flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">Sin Imagen</div>
        </a>
    @endif
    <div class="flex-1">
        <div class="flex items-center gap-2 mb-2">
            <span class="bg-red-primary text-white text-xs font-semibold px-2.5 py-0.5 rounded">
                {{ $article->category->name ?? 'General' }}
            </span>
            @if($article->published_at)
                <span class="text-gray-500 [html[data-theme=light]_&]:text-gray-600 text-xs transition-colors duration-200">{{ $article->published_at->translatedFormat('d M, Y') }}</span>
            @endif
        </div>
        <h2 class="text-xl font-bold text-white [html[data-theme=light]_&]:text-gray-900 mb-2 leading-tight transition-colors duration-200">
            <a href="{{ route('article.show', $article->slug) }}" class="hover:text-red-primary">
                {{ $article->title }}
            </a>
        </h2>
        @if($showExcerpt)
            <p class="text-gray-400 [html[data-theme=light]_&]:text-gray-600 mb-4 line-clamp-3 transition-colors duration-200">{{ $article->excerpt }}</p>
        @endif
        <a href="{{ route('article.show', $article->slug) }}" class="text-red-primary font-semibold text-sm hover:underline">
            Leer más &rarr;
        </a>
    </div>
</article>