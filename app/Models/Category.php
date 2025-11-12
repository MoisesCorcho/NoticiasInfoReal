<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasHierarchy;

class Category extends Model
{
    use HasFactory, HasHierarchy;

    protected $primaryKey = 'id_category'; // Definimos la PK personalizada

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'category_id', 'id_category');
    }

    // Relación: Una categoría puede tener subcategorías (hijos)
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id_category');
    }

    // Relación: Una categoría pertenece a una categoría padre
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id_category');
    }

    public function homepageSection(): HasMany
    {
        return $this->hasMany(HomepageSection::class, 'category_id', 'id');
    }
}
