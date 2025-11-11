<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

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

        $featuredCategory = Category::where('is_featured', true)->first();
        $featuredCategoryArticles = [];

        if ($featuredCategory) {
            $featuredCategoryArticles = Article::query()
                ->published()
                ->where('category_id', $featuredCategory->id_category)
                // ->orWhereIn('category_id', $featuredCategory->children->pluck('id_category')) // Opcional: incluir hijos
                ->with('category')
                ->latest('published_at')
                ->take(10) // Tomamos 10 para el carrusel
                ->get();
        }

        return view('livewire.home-page', [
            'heroArticles' => $heroArticles,
            'latestArticles' => $latestArticles,
            'featuredCategory' => $featuredCategory,
            'featuredCategoryArticles' => $featuredCategoryArticles,
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
