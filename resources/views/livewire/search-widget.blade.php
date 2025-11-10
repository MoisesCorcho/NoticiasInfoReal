<div class="bg-white p-6 rounded-lg shadow-sm">
    <h4 class="text-lg font-bold text-gray-800 mb-4 uppercase border-b-2 border-red-600 pb-2 inline-block">Buscar</h4>
    {{-- Usamos wire:submit para manejar el envío del formulario --}}
    <form wire:submit="search" class="flex">
        <input 
            type="text" 
            wire:model="query" 
            placeholder="Buscar noticias..." 
            class="w-full border-gray-300 rounded-l-md focus:ring-red-500 focus:border-red-500"
            required
        >
        <button type="submit" class="bg-red-600 text-white px-4 rounded-r-md hover:bg-red-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </button>
    </form>
</div>