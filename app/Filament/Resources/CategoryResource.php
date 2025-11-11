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
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

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
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship('parent', 'name', function (Builder $query, ?Model $record) {
                                if ($record) {
                                    $query->where('id_category', '!=', $record->getKey());
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->placeholder('None (Top Level Category)')
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

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Destacar en carrusel de portada')
                            ->helperText('Solo una categoría puede estar destacada a la vez.')
                            ->columnSpanFull()
                            ->rules([
                                // Regla de validación para asegurar que solo haya UN destacado
                                fn (?Model $record): \Closure => function (string $attribute, $value, \Closure $fail) use ($record) {
                                    if ($value) {
                                        $query = Category::where('is_featured', true);
                                        if ($record) {
                                            $query->where('id_category', '!=', $record->getKey());
                                        }
                                        if ($query->exists()) {
                                            $fail('Ya existe otra categoría destacada. Solo puede haber una.');
                                        }
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
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('children_count')
                    ->counts('children')
                    ->label('Direct Children')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                Tables\Columns\TextColumn::make('children.name')
                    ->label('Children List')
                    ->badge()
                    ->separator(',') // Separa los nombres con comas si no usas badges, o crea badges individuales
                    ->limitList(3)   // Muestra solo los primeros 3 y luego "+X more"
                    ->color('gray'),

                // Conteo total de descendientes (recursivo)
                Tables\Columns\TextColumn::make('descendants_count')
                    ->label('Total Descendants')
                    ->getStateUsing(fn (Model $record, CategoryService $service) => $service->countTotalDescendants($record))
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Destacada')
                    ->sortable()
                    ->updateStateUsing(function (Model $record, $state): bool {
                        // $state es el nuevo valor (true o false)
                        try {
                            DB::transaction(function () use ($record, $state) {
                                if ($state === true) {
                                    // 1. Si se activa esta, desactivar todas las demás
                                    Category::where('id_category', '!=', $record->id_category)
                                        ->where('is_featured', true)
                                        ->update(['is_featured' => false]);
                                }
                                
                                // 2. Actualizar el registro actual
                                $record->update(['is_featured' => $state]);
                            });
                            
                            Notification::make()
                                ->title('Categoría destacada actualizada')
                                ->success()
                                ->send();

                            return $state;

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al actualizar')
                                ->body('No se pudo actualizar la categoría.')
                                ->danger()
                                ->send();
                            
                            // Revertir el estado visual del toggle en caso de error
                            return !$state; 
                        }
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\Filter::make('top_level')
                    ->label('Top Level Only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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