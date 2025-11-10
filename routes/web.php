<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomePage;
use App\Livewire\ShowArticle;

Route::get('/', HomePage::class)->name('home');
Route::get('/{slug}', ShowArticle::class)->name('article.show');