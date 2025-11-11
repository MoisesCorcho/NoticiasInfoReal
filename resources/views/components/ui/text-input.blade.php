@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'bg-[#18181C] text-gray-300 border-gray-700 focus:border-[#d71935] focus:ring-[#d71935] rounded-md px-4 py-2.5 text-base placeholder:text-gray-500'
    ]) !!}
>