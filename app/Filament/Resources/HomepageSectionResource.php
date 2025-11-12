<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Models\HomepageSection;
use App\Enums\EnumHomepageSectionLayout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';
    protected static ?string $navigationGroup = 'Gestión de Contenido';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Secciones de portada';
    protected static ?string $modelLabel = 'sección de portada';
    protected static ?string $pluralModelLabel = 'secciones de portada';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Categoría')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Selecciona la categoría cuyos artículos quieres mostrar en la portada.'),

                Forms\Components\TextInput::make('display_title')
                    ->label('Título Personalizado (Opcional)')
                    ->helperText('Si lo dejas vacío, se usará el nombre de la categoría (ej. "Deportes").'),

                Forms\Components\Select::make('layout')
                    ->label('Estilo de Sección (Layout)')
                    // Usamos las opciones del Enum si existe, o el array manual si no
                    ->options(
                        class_exists(EnumHomepageSectionLayout::class) 
                            ? EnumHomepageSectionLayout::labels() 
                            : [
                                'grid' => 'Grid (Rejilla de 6 artículos)',
                                'carousel' => 'Carrusel (Deslizante horizontal)',
                                'magazine' => 'Magazine (1 grande + 4 pequeños)',
                            ]
                    )
                    ->default('grid')
                    ->required()
                    ->helperText('Elige cómo se deben mostrar los artículos de esta sección.'),

                Forms\Components\TextInput::make('display_order')
                    ->label('Orden de Visualización')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->unique(ignoreRecord: true) 
                    ->validationMessages([
                        'unique' => 'Ya existe otra sección con este número de orden. Por favor, elige otro o cambia el existente.',
                    ])
                    ->helperText('0 primero, 1 después, etc.'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Mostrar en la Portada')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. CATEGORÍA (Destacada)
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->weight('bold') // Texto en negrita
                    ->icon('heroicon-o-tag') // Icono de etiqueta
                    ->color('primary'),

                // 2. TÍTULO (Con descripción inteligente)
                Tables\Columns\TextColumn::make('display_title')
                    ->label('Título Público')
                    ->searchable()
                    ->placeholder('—')
                    ->description(fn (HomepageSection $record): string => $record->display_title ? 'Personalizado' : 'Por defecto (Nombre Categoría)')
                    ->color('gray'),

                // 3. LAYOUT (Columna nueva profesional)
                Tables\Columns\TextColumn::make('layout')
                    ->label('Diseño')
                    ->badge() // Formato tipo etiqueta
                    ->formatStateUsing(fn (EnumHomepageSectionLayout $state): string => match ($state->value) { // <-- FIX: Type hint y ->value
                        'grid' => 'Grid / Rejilla',
                        'carousel' => 'Carrusel',
                        'magazine' => 'Magazine',
                        default => ucfirst($state->value),
                    })
                    ->icon(fn (EnumHomepageSectionLayout $state): ?string => match ($state->value) { // <-- FIX: Type hint y ->value
                        'grid' => 'heroicon-m-squares-2x2',
                        'carousel' => 'heroicon-m-film',
                        'magazine' => 'heroicon-m-newspaper',
                        default => null,
                    })
                    ->color(fn (EnumHomepageSectionLayout $state): string => match ($state->value) { // <-- FIX: Type hint y ->value
                        'grid' => 'info',      // Azul
                        'carousel' => 'warning',  // Naranja/Amarillo
                        'magazine' => 'success',  // Verde
                        default => 'gray',
                    }),

                // 4. ORDEN
                Tables\Columns\TextColumn::make('display_order')
                    ->label('Orden')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                // 5. ESTADO
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
            ])
            ->filters([
                // Podrías agregar un filtro por layout aquí si quisieras
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ])
            ->defaultSort('display_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}