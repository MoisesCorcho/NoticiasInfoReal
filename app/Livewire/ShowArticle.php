<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Illuminate\View\View;

class ShowArticle extends Component
{
    public Article $article;

    // Usamos mount para cargar el artículo por slug al iniciar el componente
    public function mount($slug): void
    {
        // Buscamos el artículo publicado por su slug, o mostramos 404 si no existe
        $this->article = Article::query()
            ->published()
            ->where('slug', $slug)
            ->with(['category', 'author', 'tags', 'comments' => function ($query) {
                $query->where('status', 'approved')->latest();
            }])
            ->firstOrFail();
    }

    public function render(): View
    {
        // Obtenemos artículos recientes para la barra lateral
        $recentArticles = Article::query()
            ->published()
            ->where('id_article', '!=', $this->article->id_article)
            ->latest('published_at')
            ->take(5)
            ->get();

        // Obtenemos el artículo anterior y siguiente para la navegación
        $previousArticle = Article::query()
            ->published()
            ->where('published_at', '<', $this->article->published_at)
            ->latest('published_at')
            ->first();

        $nextArticle = Article::query()
            ->published()
            ->where('published_at', '>', $this->article->published_at)
            ->oldest('published_at')
            ->first();

        // Puede que salga error linter por el title, pero se puede ignorar.
        return view('livewire.show-article', [
            'recentArticles' => $recentArticles,
            'previousArticle' => $previousArticle,
            'nextArticle' => $nextArticle,
        ])->title($this->article->title . ' - El Notición'); // Establece el título de la página
    }
}
