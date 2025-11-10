<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

trait HasHierarchy
{
    /**
     * Define el nombre de la columna clave foránea para el padre.
     */
    public function getParentColumnName(): string
    {
        return 'parent_id';
    }

    /**
     * Define la profundidad máxima permitida para la jerarquía.
     * Ajusta este valor según tus necesidades (ej. 3 niveles: Abuelo -> Padre -> Hijo).
     */
    public function getMaxDepth(): int
    {
        return 3;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, $this->getParentColumnName());
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, $this->getParentColumnName());
    }

    /**
     * Calcula la profundidad actual del nodo en el árbol.
     * 0 para nodos raíz.
     */
    public function getDepth(): int
    {
        return $this->parent ? $this->parent->getDepth() + 1 : 0;
    }

    /**
     * Verifica si este nodo puede tener hijos basado en la profundidad máxima.
     */
    public function canHaveChildren(): bool
    {
        return $this->getDepth() < ($this->getMaxDepth() - 1);
    }

    /**
     * Obtiene todos los ancestros hacia arriba en la jerarquía.
     */
    public function getAllAncestors(): Collection
    {
        $ancestors = collect([]);
        $parent = $this->parent;

        while ($parent) {
            $ancestors->push($parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Detecta si asignar un nuevo padre crearía un ciclo infinito.
     * Es verdadero si el nuevo padre es descendiente actual de este nodo.
     */
    public function hasCircularReference(?int $newParentId): bool
    {
        if (is_null($newParentId)) {
            return false; // Si no tiene padre, no hay ciclo posible
        }

        // Un nodo no puede ser su propio padre
        if ($this->getKey() == $newParentId) {
            return true;
        }

        // Si el registro aún no existe, solo validamos que no sea él mismo (ya hecho arriba)
        if (! $this->exists) {
            return false;
        }

        // Buscamos el modelo del nuevo padre propuesto
        $newParent = static::find($newParentId);
        if (! $newParent) {
            return false; // O lanzar excepción si prefieres strictness
        }

        // Verificamos si ESTE nodo actual es un ancestro del NUEVO padre propuesto.
        // Si lo es, entonces al hacer que el nuevo padre sea hijo de este nodo, cerraríamos un ciclo.
        return $newParent->getAllAncestors()->contains($this->getKeyName(), $this->getKey());
    }

    /**
     * Obtiene la ruta completa legible (ej. "Deportes > Fútbol > Liga Local")
     */
    public function getFullPath(string $separator = ' > '): string
    {
        $path = [$this->name]; // Asume que el modelo tiene un atributo 'name'
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode($separator, $path);
    }
}