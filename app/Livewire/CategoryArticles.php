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
    /**
     * @var array<int, int>
     */
    protected array $categoryIds = [];

    // Usamos mount para recibir el slug de la categoría desde la ruta
    public function mount($slug)
    {
        $this->category = Category::with('children')->where('slug', $slug)->firstOrFail();
        $this->categoryIds = $this->collectCategoryIds($this->category);
    }

    public function render()
    {
        // Obtenemos los artículos de esta categoría, paginados.
        // Opcional: Podrías querer incluir también artículos de subcategorías.
        // Para eso necesitarías una consulta un poco más avanzada usando los IDs de los hijos.
        $articles = Article::query()
            ->published()
            ->inCategories($this->categoryIds)
            ->with('author')
            ->latest('published_at')
            ->paginate(12);

        // Puede que salga error linter por el title, pero se puede ignorar.
        return view('livewire.category-articles', [
            'articles' => $articles,
        ])->with('title', $this->category->name . ' - El Notición');
    }

    /**
     * @return array<int, int>
     */
    protected function collectCategoryIds(Category $category): array
    {
        $ids = [];
        $stack = [$category];

        while (! empty($stack)) {
            /** @var \App\Models\Category $current */
            $current = array_pop($stack);

            $ids[] = $current->id_category;

            $children = Category::where('parent_id', $current->id_category)->get();

            foreach ($children as $child) {
                $stack[] = $child;
            }
        }

        return array_values(array_unique($ids));
    }
}