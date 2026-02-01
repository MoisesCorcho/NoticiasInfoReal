<?php

namespace App\Filament\Editor\Resources;

use App\Filament\Resources\ArticleResource as AdminArticleResource;
use App\Filament\Editor\Resources\ArticleResource\Pages;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class ArticleResource extends AdminArticleResource
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
