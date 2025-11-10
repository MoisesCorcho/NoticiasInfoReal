<?php

namespace App\Livewire;

use Livewire\Component;

class SearchWidget extends Component
{
    public $query = '';

    public function search()
    {
        // Redirige a la ruta de búsqueda con el término
        return redirect()->route('search', ['q' => $this->query]);
    }

    public function render()
    {
        return view('livewire.search-widget');
    }
}