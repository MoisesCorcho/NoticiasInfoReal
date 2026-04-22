<div class="space-y-12">
    {{-- SECCIÓN 1: HERO (Noticia principal grande + 2 laterales) --}}
    @if ($heroArticles->isNotEmpty())
        <x-home.hero-section :articles="$heroArticles" />
    @endif

    {{-- WIDGET DE BÚSQUEDA --}}
    <livewire:search-widget />

    {{-- SECCIÓN 2: SECCIONES DE PORTADA (Carrusel) --}}
    @php
        use App\Enums\EnumHomepageSectionLayout;
    @endphp

    @if ($homepageSectionsData->isNotEmpty())
        @foreach ($homepageSectionsData as $data)
            {{-- Solo renderizar si la sección tiene artículos --}}
            @if ($data['articles']->isNotEmpty())
                
                @switch($data['section']->layout)
                    
                    @case(EnumHomepageSectionLayout::Carousel)
                        {{-- Ya tenías este componente --}}
                        <x-home.category-carousel 
                            :category="$data['section']->category" 
                            :articles="$data['articles']" />
                        @break

                    @case(EnumHomepageSectionLayout::Grid)
                        {{-- El nuevo componente genérico de rejilla (como "Sports") --}}
                        <x-home.grid-section 
                            :section="$data['section']" 
                            :articles="$data['articles']" />
                        @break

                    @case(EnumHomepageSectionLayout::Magazine)
                        {{-- El nuevo componente "Magazine" (como "Politics" + "Science") --}}
                        <x-home.magazine-section 
                            :section="$data['section']" 
                            :articles="$data['articles']" />
                        @break

                    @default
                        {{-- Fallback por si acaso --}}
                        <x-home.grid-section 
                            :section="$data['section']" 
                            :articles="$data['articles']" />

                @endswitch
            
            @endif
        @endforeach
    @endif

    {{-- BANNER PUBLICITARIO --}}
    <x-home.ad-banner />

    {{-- SECCIÓN 3: ÚLTIMAS NOTICIAS (Grid estándar) --}}
    @if ($latestArticles->isNotEmpty())
        <x-home.latest-news :articles="$latestArticles" />
    @endif
</div>