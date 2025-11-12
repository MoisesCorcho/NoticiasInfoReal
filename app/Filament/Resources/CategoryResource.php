<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Services\CategoryService;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Category Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Categoría padre')
                            ->relationship('parent', 'name', function (Builder $query, ?Model $record) {
                                if ($record) {
                                    $query->where('id_category', '!=', $record->getKey());
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('Sin padre (categoría principal)')
                            ->rules([
                                fn (CategoryService $service, ?Model $record) => function (string $attribute, $value, \Closure $fail) use ($service, $record) {
                                    $categoryToValidate = $record ?? new Category();
                                    try {
                                        $service->validateHierarchy($categoryToValidate, $value);
                                    } catch (Exception $e) {
                                        $fail($e->getMessage());
                                    }
                                },
                            ]),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Padre')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('children_count')
                    ->counts('children')
                    ->label('Hijos directos')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('children.name')
                    ->label('Lista de hijos')
                    ->badge()
                    ->separator(',') // Separa los nombres con comas si no usas badges, o crea badges individuales
                    ->limitList(3)   // Muestra solo los primeros 3 y luego "+X more"
                    ->color('gray'),

                // Conteo total de descendientes (recursivo)
                Tables\Columns\TextColumn::make('descendants_count')
                    ->label('Descendientes totales')
                    ->getStateUsing(fn (Model $record, CategoryService $service) => $service->countTotalDescendants($record))
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\Filter::make('top_level')
                    ->label('Solo principales')
                    ->query(fn (Builder $query): Builder => $query->whereNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->cantDeleteWithRelated(['articles' => 'Artículos asociados']),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}