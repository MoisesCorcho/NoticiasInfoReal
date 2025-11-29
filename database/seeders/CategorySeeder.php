<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Política'],
            ['name' => 'Sociedad'],
            ['name' => 'Cultura'],
            ['name' => 'Deportes'],
            ['name' => 'Entretenimiento'],
            ['name' => 'Opinión'],
            ['name' => 'Educación y Ciencia'],
            ['name' => 'Economía y Empresa'],
            ['name' => 'Ambiental'],
        ];

        foreach ($categories as $categoryData) {
            $this->seedCategory($categoryData);
        }
    }

    /**
     * @param array{name:string,children?:array<int, array|string>}|string $categoryData
     */
    protected function seedCategory(array|string $categoryData, ?int $parentId = null): void
    {
        if (is_string($categoryData)) {
            $categoryData = [
                'name' => $categoryData,
                'children' => [],
            ];
        }

        $category = Category::create([
            'name' => $categoryData['name'],
            'slug' => Str::slug($categoryData['name']),
            'parent_id' => $parentId,
        ]);

        $children = $categoryData['children'] ?? [];

        foreach ($children as $child) {
            $this->seedCategory($child, $category->id_category);
        }
    }
}

