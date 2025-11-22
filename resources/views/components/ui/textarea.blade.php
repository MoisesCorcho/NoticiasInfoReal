@props(['disabled' => false])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'bg-[#18181C] [html[data-theme=light]_&]:bg-white text-gray-300 [html[data-theme=light]_&]:text-gray-900 border-gray-700 [html[data-theme=light]_&]:border-gray-300 focus:border-red-primary focus:ring-red-primary rounded-md px-4 py-3 text-base placeholder:text-gray-500 [html[data-theme=light]_&]:placeholder:text-gray-400 transition-colors duration-200'
    ]) !!}
></textarea>