<div class="space-y-12">
    {{-- SECCIÓN 1: HERO (Noticia principal grande + 2 laterales) --}}
    @if ($heroArticles->isNotEmpty())
        <x-home.hero-section :articles="$heroArticles" />
    @endif

    {{-- SECCIÓN 2: ÚLTIMAS NOTICIAS (Grid estándar) --}}
    <section id="latest-news">
        <x-home.latest-news :articles="$latestArticles" />
    </section>

    {{-- SECCIÓN 3: CATEGORÍAS ESPECÍFICAS (Dos columnas) --}}
    @if ($featuredCategory)
        <x-home.category-carousel :category="$featuredCategory" :articles="$featuredCategoryArticles" />
    @endif
</div>
