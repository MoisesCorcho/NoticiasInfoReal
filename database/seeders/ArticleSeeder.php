<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $tags = Tag::all();

        if ($categories->isEmpty() || $tags->isEmpty()) {
            $this->command?->warn('Categories or Tags not found. Skipping ArticleSeeder.');
            return;
        }

        $authors = User::factory()->count(5)->create();

        Article::factory()
            ->count(200)
            ->published()
            ->state(fn() => [
                'user_id' => $authors->random()->id,
                'category_id' => $categories->random()->id_category,
            ])
            ->create()
            ->each(function (Article $article) use ($tags) {
                $tagIds = $tags->random(rand(1, min(5, $tags->count())))->pluck('id_tag')->all();
                $article->tags()->sync($tagIds);
            });

        Article::factory()
            ->count(50)
            ->state(fn() => [
                'user_id' => $authors->random()->id,
                'category_id' => $categories->random()->id_category,
            ])
            ->create()
            ->each(function (Article $article) use ($tags) {
                $tagIds = $tags->random(rand(1, min(5, $tags->count())))->pluck('id_tag')->all();
                $article->tags()->sync($tagIds);
            });
    }
}
