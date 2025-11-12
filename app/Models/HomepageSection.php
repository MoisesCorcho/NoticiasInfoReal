<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\EnumHomepageSectionLayout;

class HomepageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'display_title',
        'display_order',
        'is_active',
        'layout',
    ];

    protected $casts = [
        'layout' => EnumHomepageSectionLayout::class,
    ];

    // Relación: Una sección de portada pertenece a UNA categoría
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }
}
