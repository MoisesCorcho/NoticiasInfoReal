<?php

namespace Database\Factories;

use App\Enums\EnumArticleStatus;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);
        $status = $this->faker->randomElement(EnumArticleStatus::values());

        $relativeImagePath = 'images/image_article_example.jpg';
        $absoluteImagePath = public_path($relativeImagePath);
        $featuredImageUrl = file_exists($absoluteImagePath) ? $relativeImagePath : null;

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'content' => $this->faker->paragraphs(5, true),
            'excerpt' => $this->faker->sentences(2, true),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'published_at' => $this->resolvePublishedAt($status),
            'status' => $status,
            'featured_image_url' => $featuredImageUrl,
            'allows_comments' => $this->faker->boolean(80),
        ];
    }

    private function resolvePublishedAt(string $status): ?\DateTimeInterface
    {
        return match ($status) {
            EnumArticleStatus::Published->value,
            EnumArticleStatus::Scheduled->value => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            default => null,
        };
    }

    public function published(): static
    {
        return $this->state(function (array $attributes) {
            $dateTime = $this->faker->dateTimeBetween('-1 month', 'now');

            return [
                'status' => EnumArticleStatus::Published->value,
                'published_at' => $dateTime,
            ];
        });
    }

    public function randomizedStatus(): static
    {
        return $this->state(function (array $attributes) {
            $status = $this->faker->randomElement(EnumArticleStatus::values());

            return [
                'status' => $status,
                'published_at' => $this->resolvePublishedAt($status),
            ];
        });
    }
}

