<?php

namespace App\Filament\Resources\ArticleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Enums\EnumCommentStatus;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $recordTitleAttribute = 'content'; // Usamos el contenido como "título" del registro

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('author_name')
                    ->label('Autor')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('author_email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options(EnumCommentStatus::labels())
                    ->required()
                    ->native(false)
                    ->default(EnumCommentStatus::Pending->value),

                Forms\Components\Textarea::make('content')
                    ->label('Comentario')
                    ->required()
                    ->columnSpanFull()
                    ->rows(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('author_name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Model $record): string => $record->author_email), // Muestra el email debajo del nombre

                Tables\Columns\TextColumn::make('content')
                    ->label('Comentario')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        EnumCommentStatus::Pending->value => 'warning',
                        EnumCommentStatus::Approved->value => 'success',
                        EnumCommentStatus::Spam->value => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options(EnumCommentStatus::labels()),
            ])
            ->headerActions([
                // Generalmente no se crean comentarios desde el panel de admin, pero se puede dejar si es útil
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Acciones rápidas de moderación
                Tables\Actions\Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Model $record) => $record->update(['status' => EnumCommentStatus::Approved->value]))
                    ->visible(fn (Model $record): bool => $record->status !== EnumCommentStatus::Approved->value),

                Tables\Actions\Action::make('mark_as_spam')
                    ->label('Marcar como spam')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->action(fn (Model $record) => $record->update(['status' => EnumCommentStatus::Spam->value]))
                    ->visible(fn (Model $record): bool => $record->status !== EnumCommentStatus::Spam->value),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Acciones masivas también son útiles
                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Aprobar seleccionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => EnumCommentStatus::Approved->value]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}