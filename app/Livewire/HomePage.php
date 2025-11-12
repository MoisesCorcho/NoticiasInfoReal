<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use App\Models\HomepageSection;
use App\Enums\EnumHomepageSectionLayout;

class HomePage extends Component
{
    public function render()
    {
        // 1. Hero Section: Las 3 noticias más recientes e importantes
        $heroArticles = Article::query()
            ->published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // 2. Últimas noticias (excluyendo las del hero para no repetir)
        $heroIds = $heroArticles->pluck('id_article');
        $latestArticles = Article::query()
            ->published()
            ->whereNotIn('id_article', $heroIds)
            ->with('category')
            ->latest('published_at')
            ->take(6)
            ->get();

        $homepageSections = HomepageSection::query()
            ->where('is_active', true)
            ->with('category') // Cargar la relación con la categoría
            ->orderBy('display_order', 'asc')
            ->get();

        // Preparar los datos para la vista: un array que contiene la sección y sus artículos
        $homepageSectionsData = $homepageSections->map(function ($section) use ($heroIds) {
            
            // Determinar cuántos artículos buscar según el layout
            $limit = match ($section->layout) {
                EnumHomepageSectionLayout::Carousel => 10, // 10 para el carrusel
                EnumHomepageSectionLayout::Grid => 6,     // 6 para la rejilla (como "Sports")
                EnumHomepageSectionLayout::Magazine => 5, // 5 para el magazine (1 grande + 4 pequeños)
            };

            $articles = Article::query()
                ->published()
                ->where('category_id', $section->category_id)
                ->whereNotIn('id_article', $heroIds) // No repetir artículos del hero
                ->with('category')
                ->latest('published_at')
                ->take($limit)
                ->get();

            return [
                'section' => $section,
                'articles' => $articles,
            ];
        });

        return view('livewire.home-page', [
            'heroArticles' => $heroArticles,
            'latestArticles' => $latestArticles,
            'homepageSectionsData' => $homepageSectionsData,
        ]);
    }

    private function getArticlesByCategory(string $slug, int $limit = 4)
    {
        return Article::query()
            ->published()
            ->whereHas('category', fn($q) => $q->where('slug', $slug))
            ->with('category')
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
