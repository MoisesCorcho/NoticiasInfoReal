<?php

namespace App\Filament\Resources\ArticleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $recordTitleAttribute = 'content'; // Usamos el contenido como "título" del registro

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('author_name')
                    ->label('Author')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('author_email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'spam' => 'Spam',
                    ])
                    ->required()
                    ->native(false),

                Forms\Components\Textarea::make('content')
                    ->label('Comment')
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
                    ->label('Author')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Model $record): string => $record->author_email), // Muestra el email debajo del nombre

                Tables\Columns\TextColumn::make('content')
                    ->label('Comment')
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
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'spam' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'spam' => 'Spam',
                    ]),
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
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(fn (Model $record) => $record->update(['status' => 'approved']))
                    ->visible(fn (Model $record): bool => $record->status !== 'approved'),

                Tables\Actions\Action::make('mark_as_spam')
                    ->label('Spam')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->action(fn (Model $record) => $record->update(['status' => 'spam']))
                    ->visible(fn (Model $record): bool => $record->status !== 'spam'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Acciones masivas también son útiles
                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'approved']))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}