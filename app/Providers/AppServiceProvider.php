<?php

namespace App\Providers;

use Filament\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\DeleteAction as TablesDeleteAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $macro = function (array $relationships, ?string $message = null) {
            /** @var TablesDeleteAction|ActionsDeleteAction $this */
            $message ??= __('No se puede eliminar porque existen registros relacionados: :relations.');

            return $this->before(function ($action, $record) use ($relationships, $message) {
                $blocked = [];

                foreach ($relationships as $key => $value) {
                    $relation = is_int($key) ? $value : $key;
                    $label = is_int($key) ? Str::headline($value) : $value;

                    if (! method_exists($record, $relation)) {
                        continue;
                    }

                    $count = $record->{$relation}()->count();

                    if ($count > 0) {
                        $blocked[] = "{$label} ({$count})";
                    }
                }

                if (! empty($blocked)) {
                    Notification::make()
                        ->title(__('Operación no permitida'))
                        ->body(str_replace(':relations', implode(', ', $blocked), $message))
                        ->danger()
                        ->send();

                    throw new Halt();
                }
            });
        };

        TablesDeleteAction::macro('cantDeleteWithRelated', $macro);
        ActionsDeleteAction::macro('cantDeleteWithRelated', $macro);
    }
}
