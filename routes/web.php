<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\ShowArticle;
use App\Livewire\SearchResults;

Route::get('/', HomePage::class)->name('home');
Route::get('/buscar', SearchResults::class)->name('search');
Route::get('/{slug}', ShowArticle::class)->name('article.show');