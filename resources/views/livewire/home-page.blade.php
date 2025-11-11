<div class="space-y-12">
    {{-- SECCIÓN 1: HERO (Noticia principal grande + 2 laterales) --}}
    @if ($heroArticles->isNotEmpty())
        <x-home.hero-section :articles="$heroArticles" />
    @endif

    {{-- SECCIÓN 2: ÚLTIMAS NOTICIAS (Grid estándar) --}}
    @if ($latestArticles->isNotEmpty())
        <x-home.latest-news :articles="$latestArticles" />
    @endif

    {{-- SECCIÓN 3: CATEGORÍAS ESPECÍFICAS (Carrusel) --}}
    @if ($featuredCategory)
        <x-home.category-carousel :category="$featuredCategory" :articles="$featuredCategoryArticles" />
    @endif
</div>