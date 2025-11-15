@props([
    'category',
    'separator' => ' > ',
    'class' => 'text-sm text-gray-400',
])

@php
    /** @var \App\Models\Category $category */
    $ancestors = $category->getAllAncestors()->reverse()->values();
@endphp

<nav aria-label="Ruta de categorías" {{ $attributes->merge(['class' => $class]) }}>
    <ol class="flex flex-wrap items-center">
        @foreach ($ancestors as $index => $ancestor)
            <li class="inline-flex items-center">
                <a href="{{ route('category.show', $ancestor->slug) }}" class="hover:text-white">
                    {{ $ancestor->name }}
                </a>
            </li>
            <li class="mx-2 select-none">{{ $separator }}</li>
        @endforeach
        <li class="inline-flex items-center text-white">
            <a href="{{ route('category.show', $category->slug) }}" class="hover:text-white">
                {{ $category->name }}
            </a>
        </li>
    </ol>
</nav>


