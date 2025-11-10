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

        // 3. Secciones por categoría (Ejemplo: Deportes y Región)
        // Usamos un método auxiliar para no repetir código
        $sportsArticles = $this->getArticlesByCategory('deportes');
        $regionArticles = $this->getArticlesByCategory('region'); // Asegúrate que el slug coincida con tu DB

        return view('livewire.home-page', [
            'heroArticles' => $heroArticles,
            'latestArticles' => $latestArticles,
            'sportsArticles' => $sportsArticles,
            'regionArticles' => $regionArticles,
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
