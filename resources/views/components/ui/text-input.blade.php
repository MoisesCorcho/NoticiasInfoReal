@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'bg-[#4a494a] [html[data-theme=light]_&]:bg-gray-100 text-gray-200 [html[data-theme=light]_&]:text-gray-900 border-gray-600 [html[data-theme=light]_&]:border-gray-300 focus:border-[#d71935] focus:ring-[#d71935] rounded-md px-4 py-2.5 text-base placeholder:text-gray-400 [html[data-theme=light]_&]:placeholder:text-gray-500 transition-colors duration-200'
    ]) !!}
>