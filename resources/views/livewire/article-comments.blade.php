<section id="comments" class="bg-[#18181C] p-6 rounded-lg border border-white/10 scroll-mt-20">
    <h3 class="text-2xl font-bold text-white mb-8">
        Comentarios ({{ $article->comments()->where('status', 'approved')->count() }})
    </h3>

    {{-- LISTADO DE COMENTARIOS --}}
    <div class="space-y-8 mb-12">
        @forelse($comments as $comment)
            <div class="flex gap-4">
                <div class="shrink-0">
                    {{-- Avatar (asumimos que x-ui.avatar-initials se ve bien) --}}
                    <x-ui.avatar-initials :name="$comment->author_name" />
                </div>
                <div class="flex-1 border-b border-white/10 pb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="font-bold text-white">
                            {{ $comment->author_name }}
                        </h5>
                        <span class="text-xs text-gray-400">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="prose prose-sm max-w-none prose-invert text-gray-300">
                        {!! nl2br(e($comment->content)) !!}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-400 italic text-center py-4">
                Aún no hay comentarios. ¡Sé el primero en opinar!
            </p>
        @endforelse

        {{-- Paginación si hay muchos comentarios --}}
        <div class="mt-4">
            {{ $comments->links() }}
        </div>
    </div>

    {{-- FORMULARIO DE COMENTARIOS --}}
    <div class="bg-[#333233] p-6 rounded-lg border border-white/10">
        <h4 class="text-lg font-bold text-white mb-4">Deja tu opinión</h4>

        {{-- Mensaje de éxito --}}
        @if (session()->has('success'))
            <div class="mb-4 p-4 bg-green-900/50 text-green-300 rounded-lg border border-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="author_name" class="block text-sm font-medium text-gray-300 mb-1">Nombre *</label>
                    <x-ui.text-input 
                        type="text" 
                        wire:model="author_name" 
                        id="author_name"
                        class="w-full"
                    />
                    @error('author_name') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="author_email" class="block text-sm font-medium text-gray-300 mb-1">Email * (no será publicado)</label>
                    <x-ui.text-input 
                        type="email" 
                        wire:model="author_email" 
                        id="author_email"
                        class="w-full"
                    />
                    @error('author_email') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Comentario *</label>
                <x-ui.textarea 
                    wire:model="content" 
                    id="content" 
                    rows="4"
                    class="w-full"
                    placeholder="Escribe aquí tu comentario..."
                />
                @error('content') <span class="text-red-600 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                    class="bg-[#d71935] text-white px-6 py-2 rounded-md font-bold uppercase text-sm hover:bg-red-700 transition disabled:opacity-50"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Publicar Comentario</span>
                <span wire:loading>Enviando...</span>
            </button>
        </form>
    </div>
</section>