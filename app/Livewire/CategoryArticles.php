<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryArticles extends Component
{
    use WithPagination;

    public Category $category;

    // Usamos mount para recibir el slug de la categoría desde la ruta
    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        // Obtenemos los artículos de esta categoría, paginados.
        // Opcional: Podrías querer incluir también artículos de subcategorías.
        // Para eso necesitarías una consulta un poco más avanzada usando los IDs de los hijos.
        $articles = Article::query()
            ->published()
            ->where('category_id', $this->category->id_category)
            ->with('author')
            ->latest('published_at')
            ->paginate(12);

        // Puede que salga error linter por el title, pero se puede ignorar.
        return view('livewire.category-articles', [
            'articles' => $articles,
        ])->title($this->category->name . ' - El Notición');
    }
}