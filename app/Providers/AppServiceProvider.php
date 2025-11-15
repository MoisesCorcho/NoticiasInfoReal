<?php

namespace App\Providers;

use Filament\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\DeleteAction as TablesDeleteAction;
use Illuminate\Database\Eloquent\SoftDeletes;
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
        // Macro: cantDeleteWithRelated
        // Agrega una verificación previa a acciones Delete de Filament (tablas y acciones)
        // para impedir la eliminación cuando el registro tiene relaciones con datos existentes.
        // - Recorre las relaciones indicadas y calcula sus conteos.
        // - Si hay alguna con registros (> 0), muestra una notificación descriptiva
        //   y lanza Halt() para cancelar la acción de borrado.
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

        /**
         * Macro: confirmPurgeTrashedRelated
         * Muestra confirmación si hay relacionados en papelera y, si se confirma,
         * los elimina definitivamente antes de proceder.
         *
         * Opciones por defecto personalizables mediante $options:
         * - heading
         * - submitLabel
         * - icon
         */
        $confirmAndPurge = function (array $relationships, array $options = []) {
            /** @var TablesDeleteAction|ActionsDeleteAction $this */
            $heading = $options['heading'] ?? '¿Eliminar registro?';
            $submitLabel = $options['submitLabel'] ?? 'Sí, eliminar y purgar';
            $icon = $options['icon'] ?? 'heroicon-o-exclamation-triangle';

            return $this
                ->requiresConfirmation(function ($record) use ($relationships): bool {
                    foreach ($relationships as $relation) {
                        if (! method_exists($record, $relation)) {
                            continue;
                        }
                        $query = $record->{$relation}();
                        $model = $query->getModel();
                        if (! in_array(SoftDeletes::class, class_uses_recursive($model))) {
                            continue;
                        }
                        if ($query->onlyTrashed()->exists()) {
                            return true;
                        }
                    }
                    return false;
                })
                ->modalHeading($heading)
                ->modalDescription(function ($record) use ($relationships): string {
                    $total = 0;
                    foreach ($relationships as $relation) {
                        if (! method_exists($record, $relation)) {
                            continue;
                        }
                        $query = $record->{$relation}();
                        $model = $query->getModel();
                        if (! in_array(SoftDeletes::class, class_uses_recursive($model))) {
                            continue;
                        }
                        $total += $query->onlyTrashed()->count();
                    }
                    return "Se eliminarán definitivamente {$total} registro(s) que están en la papelera y pertenecen a este registro. ¿Deseas continuar?";
                })
                ->modalSubmitActionLabel($submitLabel)
                ->modalIcon($icon)
                ->before(function ($record) use ($relationships): void {
                    foreach ($relationships as $relation) {
                        if (! method_exists($record, $relation)) {
                            continue;
                        }
                        $query = $record->{$relation}();
                        $model = $query->getModel();
                        if (! in_array(SoftDeletes::class, class_uses_recursive($model))) {
                            continue;
                        }
                        $query->onlyTrashed()->get()->each->forceDelete();
                    }
                });
        };

        TablesDeleteAction::macro('confirmPurgeTrashedRelated', $confirmAndPurge);
        ActionsDeleteAction::macro('confirmPurgeTrashedRelated', $confirmAndPurge);
    }
}
