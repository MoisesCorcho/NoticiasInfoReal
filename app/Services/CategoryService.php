<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class CategoryService
{
    /**
     * Valida que la jerarquía sea correcta antes de guardar.
     * Lanza excepciones si se violan las reglas de negocio.
     */
    public function validateHierarchy(Category $category, ?int $newParentId): void
    {
        // 1. Validar Referencia Circular
        if ($category->hasCircularReference($newParentId)) {
            throw new Exception('Circular reference detected: A category cannot be a descendant of itself.');
        }

        // 2. Validar Profundidad Máxima (si se asigna un padre)
        if ($newParentId) {
            try {
                $parent = Category::findOrFail($newParentId);
            } catch (ModelNotFoundException $e) {
                throw new Exception('Selected parent category does not exist.');
            }

            // Verificamos si el PADRE puede tener más hijos según SU profundidad
            if (! $parent->canHaveChildren()) {
                throw new Exception("Maximum hierarchy depth of {$category->getMaxDepth()} reached. Cannot add more sublevels here.");
            }
        }
    }

    /**
     * Cuenta el total absoluto de descendientes (hijos, nietos, etc.) de forma recursiva.
     */
    public function countTotalDescendants(Category $category): int
    {
        $count = 0;
        // Cargamos los hijos si no están cargados para evitar N+1 excesivo en bucles
        $children = $category->relationLoaded('children') ? $category->children : $category->children()->get();

        foreach ($children as $child) {
            // Sumamos 1 por el hijo actual + sus propios descendientes
            $count += 1 + $this->countTotalDescendants($child);
        }

        return $count;
    }

    /**
     * Maneja la limpieza de relaciones cuando una categoría se elimina o mueve.
     * Por ejemplo, si se borra una categoría padre, ¿qué pasa con los hijos?
     * Opción A: Se borran en cascada (configurado en DB usually).
     * Opción B: Se mueven al nivel raíz (húerfanos).
     */
    public function handleOrphans(Category $category): void
    {
        // Ejemplo: Mover hijos al nivel raíz (parent_id = null)
        // $category->children()->update(['parent_id' => null]);

        // Ejemplo 2: Mover hijos al padre del nodo eliminado (subir un nivel)
        $newParentId = $category->parent_id; // Puede ser null si era raíz
        $category->children()->update(['parent_id' => $newParentId]);
    }
}
