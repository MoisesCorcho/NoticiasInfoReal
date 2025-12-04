<?php

namespace Database\Seeders;

use App\Enums\EnumHomepageSectionLayout;
use App\Models\Category;
use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layouts = EnumHomepageSectionLayout::values();
        
        // Get random categories, ensuring we have enough for each layout
        $categories = Category::inRandomOrder()->limit(count($layouts))->get();

        if ($categories->count() < count($layouts)) {
            $this->command->warn('Not enough categories to seed all homepage section layouts. Please seed categories first.');
            return;
        }

        foreach ($layouts as $index => $layout) {
            HomepageSection::create([
                'category_id' => $categories[$index]->id_category,
                'display_title' => $categories[$index]->name, // Use category name as default title
                'display_order' => $index,
                'is_active' => true,
                'layout' => $layout,
            ]);
        }
    }
}
