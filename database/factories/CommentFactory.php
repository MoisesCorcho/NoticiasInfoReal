<?php

namespace Database\Factories;

use App\Enums\EnumCommentStatus;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(EnumCommentStatus::values());

        return [
            'article_id' => Article::factory(),
            'author_name' => $this->faker->name(),
            'author_email' => $this->faker->unique()->safeEmail(),
            'content' => $this->faker->paragraph(),
            'status' => $status,
        ];
    }
}

