@props(['categories'])

<ul class="pl-4 space-y-2">
    @foreach ($categories as $category)
        <li>
            <div x-data="{ open: false }" class="relative">
                <div class="flex items-center justify-between">
                    <a href="{{ route('category.show', $category->slug) }}" class="block py-1 text-gray-300 [html[data-theme=light]_&]:text-gray-700 hover:text-[#d71935] transition-colors">
                        {{ $category->name }}
                    </a>
                    @if ($category->children->isNotEmpty())
                        <button @click="open = !open" class="p-1 text-gray-400 [html[data-theme=light]_&]:text-gray-600 hover:text-[#d71935] focus:outline-none transition-colors">
                            <svg xmlns="http://www.w.g.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 transition-transform" :class="{ 'rotate-90': open }">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    @endif
                </div>

                @if ($category->children->isNotEmpty())
                    <div x-show="open" x-collapse class="mt-1 border-l-2 border-gray-700 [html[data-theme=light]_&]:border-gray-300 ml-2 transition-colors duration-200">
                        {{-- Llamada recursiva al mismo componente --}}
                        <x-ui.category-dropdown :categories="$category->children" />
                    </div>
                @endif
            </div>
        </li>
    @endforeach
</ul>