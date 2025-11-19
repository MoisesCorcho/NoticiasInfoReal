<div class="bg-[#333233] [html[data-theme=light]_&]:bg-gray-50 p-6 rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
    <h4 class="text-lg font-bold text-white [html[data-theme=light]_&]:text-gray-900 mb-4 uppercase border-b-2 border-[#d71935] pb-2 inline-block transition-colors duration-200">Buscar</h4>
    <form wire:submit="search" class="flex">
        <x-ui.text-input 
            type="text" 
            wire:model="query" 
            placeholder="Buscar noticias..." 
            class="w-full rounded-r-none border-r-0"
            required
        />
        <button type="submit" class="bg-[#d71935] text-white px-4 rounded-r-md hover:bg-red-700 transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </button>
    </form>
</div>