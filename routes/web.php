<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\ShowArticle;
use App\Livewire\SearchResults;
use App\Livewire\CategoryArticles;

Route::get('/', HomePage::class)->name('home');
Route::get('/buscar', SearchResults::class)->name('search');
Route::get('/categoria/{slug}', CategoryArticles::class)->name('category.show');
Route::get('/articulo/{slug}', ShowArticle::class)->name('article.show');