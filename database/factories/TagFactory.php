<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Elecciones 2025',
            'Congreso',
            'Inflación',
            'Mercados Globales',
            'Fútbol Femenino',
            'LaLiga',
            'Champions League',
            'Tecnología Verde',
            'Inteligencia Artificial',
            'Ciencia y Salud',
            'Arte Contemporáneo',
            'Patrimonio Cultural',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}

