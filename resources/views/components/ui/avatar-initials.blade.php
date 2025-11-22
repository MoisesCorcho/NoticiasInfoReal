@props([
    'name',
    'classes' => 'w-10 h-10 text-base',
    'background' => 'bg-[#333233] [html[data-theme=light]_&]:bg-gray-200',
    'textColor' => 'text-gray-300 [html[data-theme=light]_&]:text-gray-700',
])

@php
    $initials = collect(preg_split('/\s+/', trim((string) $name)))
        ->filter()
        ->map(fn ($segment) => mb_substr($segment, 0, 1))
        ->take(2)
        ->implode('');
@endphp

<div {{ $attributes->merge([
    'class' => "{$classes} {$background} {$textColor} rounded-full flex items-center justify-center font-bold uppercase",
]) }}>
    {{ $initials }}
</div>