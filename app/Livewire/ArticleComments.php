<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class ArticleComments extends Component
{
    use WithPagination;

    public Article $article;

    #[Rule('required|min:3|max:255')]
    public $author_name = '';

    #[Rule('required|email|max:255')]
    public $author_email = '';

    #[Rule('required|min:10|max:1000')]
    public $content = '';

    public function save()
    {
        $this->validate();

        $this->article->comments()->create([
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'content' => $this->content,
            'status' => 'pending', // O 'approved' si no quieres moderación
        ]);

        // Limpiamos solo el contenido para que puedan seguir comentando sin reescribir sus datos
        $this->reset('content');

        // Usamos flash para mostrar un mensaje temporal
        session()->flash('success', '¡Gracias! Tu comentario ha sido enviado y está pendiente de aprobación.');
    }

    public function render()
    {
        // Cargamos los comentarios aprobados, paginados para no saturar si hay muchos
        $comments = $this->article->comments()
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('livewire.article-comments', [
            'comments' => $comments
        ]);
    }
}