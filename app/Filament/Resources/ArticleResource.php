<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use App\Filament\Resources\ArticleResource\RelationManagers\CommentsRelationManager;
use App\Enums\EnumArticleStatus;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        // --- COLUMNA PRINCIPAL (Ocupa 2 de 3 espacios) ---
                        Group::make()
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Contenido del artículo')
                                    ->description('Redacta el contenido principal de la noticia.')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Título')
                                            ->required()
                                            ->live(onBlur: true) // Genera el slug cuando el usuario termina de escribir
                                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->disabled() // Usualmente el slug no se debe editar manualmente a menos que sea necesario
                                            ->dehydrated(), // Asegura que se envíe aunque esté disabled

                                        Forms\Components\RichEditor::make('content')
                                            ->label('Contenido')
                                            ->required()
                                            ->columnSpanFull()
                                            ->fileAttachmentsDirectory('articles/images'),

                                        Forms\Components\Textarea::make('excerpt')
                                            ->label('Resumen')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('Resumen corto para SEO y listados.'),
                                    ]),
                            ]),

                        // --- BARRA LATERAL (ocupa 1 de 3 columnas) ---
                        Group::make()
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Metadatos')
                                    ->schema([
                                        Forms\Components\FileUpload::make('featured_image_url')
                                            ->label('Imagen destacada')
                                            ->image()
                                            ->directory('articles/featured')
                                            ->imageEditor(),

                                        Forms\Components\Select::make('status')
                                            ->label('Estado')
                                            ->options(EnumArticleStatus::labels())
                                            ->required()
                                            ->native(false),

                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Fecha de publicación')
                                            ->native(false),

                                        Forms\Components\Toggle::make('allows_comments')
                                            ->label('Permitir comentarios')
                                            ->default(true),
                                    ]),

                                Section::make('Asociaciones')
                                    ->schema([
                                        Forms\Components\Select::make('user_id')
                                            ->relationship('author', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Autor')
                                            ->required(),

                                        Forms\Components\Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),
                                                Forms\Components\TextInput::make('slug')
                                                    ->label('Slug')
                                                    ->required()
                                                    ->unique(Category::class, 'slug'),
                                            ]),

                                        Forms\Components\Select::make('tags')
                                            ->relationship('tags', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->label('Etiquetas')
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nombre')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),
                                                Forms\Components\TextInput::make('slug')
                                                    ->label('Slug')
                                                    ->required()
                                                    ->unique(Tag::class, 'slug'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_url')
                    ->label('Imagen')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        EnumArticleStatus::Draft->value => 'gray',
                        EnumArticleStatus::Scheduled->value => 'warning',
                        EnumArticleStatus::Published->value => 'success',
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicado')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('allows_comments')
                    ->label('Comentarios')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(EnumArticleStatus::labels()),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}