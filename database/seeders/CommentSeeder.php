<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::all()->each(function (Article $article) {
            $count = rand(0, 10);

            if ($count === 0) {
                return;
            }

            Comment::factory()
                ->count($count)
                ->state([
                    'article_id' => $article->id_article,
                ])
                ->create();
        });
    }
}

