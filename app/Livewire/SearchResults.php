<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class SearchResults extends Component
{
    use WithPagination;

    #[Url(as: 'q')] 
    public $query = '';

    public function render()
    {
        $articles = [];

        if ($this->query) {
            $articles = Article::query()
                ->published()
                ->where(function($q) {
                    $q->where('title', 'like', '%' . $this->query . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->query . '%')
                      ->orWhere('content', 'like', '%' . $this->query . '%');
                })
                ->with(['category', 'author'])
                ->latest('published_at')
                ->paginate(10);
        }

        return view('livewire.search-results', [
            'articles' => $articles,
        ]);
    }
}