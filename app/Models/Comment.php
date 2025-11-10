<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_comment';

    protected $fillable = [
        'article_id',
        'author_name',
        'author_email',
        'content',
        'status',
    ];

    // Relación: Pertenece a un artículo
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id', 'id_article');
    }
}
