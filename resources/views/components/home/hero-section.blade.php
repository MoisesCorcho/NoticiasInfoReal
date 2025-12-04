@props(['articles'])

<section id="hero" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Noticia Principal (ocupa 2 columnas en pantallas grandes) --}}
    <div class="lg:hidden flex flex-col gap-4 mb-8">
        <h1 class="text-3xl font-bold leading-tight text-white [html[data-theme=light]_&]:text-gray-900">
            <a href="{{ route('article.show', $articles[0]->slug) }}">
                {{ $articles[0]->title }}
            </a>
        </h1>

        <div class="flex items-center gap-3 text-sm text-gray-400 [html[data-theme=light]_&]:text-gray-600">
            <span class="font-bold text-red-primary uppercase">
                {{ $articles[0]->category->name ?? 'General' }}
            </span>
            <span>|</span>
            <span>{{ $articles[0]->published_at->translatedFormat('F j, Y') }}</span>
        </div>

        <div class="relative aspect-video w-full overflow-hidden rounded-lg">
            @if ($articles[0]->featured_image_url)
                <img src="{{ Storage::url($articles[0]->featured_image_url) }}" alt="{{ $articles[0]->title }}"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 w-full h-full bg-[#18181C] [html[data-theme=light]_&]:bg-gray-100 items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 hidden">Sin Imagen</div>
            @else
                <div class="w-full h-full bg-[#18181C] [html[data-theme=light]_&]:bg-gray-100 flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600">Sin Imagen</div>
            @endif
        </div>

        <p class="text-gray-300 [html[data-theme=light]_&]:text-gray-700 leading-relaxed">
            {{ $articles[0]->excerpt }}
        </p>
    </div>

    {{-- Desktop Layout (Original) --}}
    <div class="hidden lg:block lg:col-span-2 group relative h-96 overflow-hidden rounded-lg border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
        {{-- Imagen de fondo que cubre todo --}}
        @if ($articles[0]->featured_image_url)
            <img src="{{ Storage::url($articles[0]->featured_image_url) }}" alt="{{ $articles[0]->title }}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 w-full h-full bg-[#18181C] [html[data-theme=light]_&]:bg-gray-100 items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 hidden transition-colors duration-200">Sin Imagen</div>
        @else
            <div class="absolute inset-0 w-full h-full bg-[#18181C] [html[data-theme=light]_&]:bg-gray-100 flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">Sin Imagen</div>
        @endif

        {{-- Degradado oscuro en modo dark, claro en modo light --}}
        {{-- Ajusta la opacidad: from-black/XX (más oscuro = número mayor, ej: /95, /90, /80, /70) --}}
        {{-- Ajusta el ancho: w-XX (w-1/2 = 50%, w-2/3 = 66%, w-3/4 = 75%, w-full = 100%) --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/100 via-black/85 to-transparent [html[data-theme=light]_&]:from-white/95 [html[data-theme=light]_&]:via-white/85 [html[data-theme=light]_&]:to-transparent w-full transition-colors duration-200"></div>

        {{-- Contenido de la noticia sobre el degradado izquierdo --}}
        <div class="absolute inset-0 flex flex-col justify-end p-6 z-10 w-2/3">
            <span class="inline-block bg-red-primary text-white text-xs font-bold px-2 py-1 mb-2 rounded w-fit">
                {{ $articles[0]->category->name ?? 'General' }}
            </span>
            <h1 class="text-3xl font-bold leading-tight hover:underline text-white [html[data-theme=light]_&]:text-gray-900 transition-colors duration-200">
                <a href="{{ route('article.show', $articles[0]->slug) }}">
                    {{ $articles[0]->title }}
                </a>
            </h1>
            <p class="text-gray-300 mt-2 text-sm line-clamp-2 [html[data-theme=light]_&]:text-gray-700 transition-colors duration-200">{{ $articles[0]->excerpt }}</p>
            <p class="text-gray-400 text-xs mt-3 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200">{{ $articles[0]->published_at->diffForHumans() }}</p>
        </div>
    </div>

    {{-- Noticias Secundarias del Hero (columna derecha) --}}
    <div class="space-y-6">
        @foreach ($articles->skip(1) as $article)
            <div class="relative h-[184px] overflow-hidden rounded-lg group border border-white/10 [html[data-theme=light]_&]:border-gray-200 transition-colors duration-200">
                @if ($article->featured_image_url)
                    <img src="{{ Storage::url($article->featured_image_url) }}" alt="{{ $article->title }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="w-full h-full bg-[#18181C] [html[data-theme=light]_&]:bg-gray-100 items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 hidden transition-colors duration-200"></div>
                @else
                    {{-- Fallback para imágenes secundarias --}}
                    <div class="w-full h-full bg-[#18181C] [html[data-theme=light]_&]:bg-gray-100 flex items-center justify-center text-gray-500 [html[data-theme=light]_&]:text-gray-600 transition-colors duration-200"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                    <div class="absolute bottom-0 p-4 text-white">
                        <span class="bg-red-primary text-[10px] font-bold px-1.5 py-0.5 rounded">
                            {{ $article->category->name ?? 'News' }}
                        </span>
                        <h3 class="font-bold text-lg leading-snug mt-1 hover:underline">
                            <a href="{{ route('article.show', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>