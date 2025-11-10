<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_article';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'user_id',
        'category_id',
        'published_at',
        'status',
        'featured_image_url',
        'allows_comments',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'allows_comments' => 'boolean',
    ];

    // Relación: Pertenece a un autor (User)
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relación: Pertenece a una categoría principal
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    // Relación: Tiene muchos comentarios
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id', 'id_article');
    }

    // Relación: Pertenece a muchas etiquetas (Muchos a Muchos)
    public function tags(): BelongsToMany
    {
        // Especificamos la tabla pivote y las llaves foráneas si no siguen la convención por defecto
        return $this->belongsToMany(Tag::class, 'article_tag', 'article_id', 'tag_id');
    }
}
