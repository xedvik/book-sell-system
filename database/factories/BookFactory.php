<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Около 3-5 слов
            'description' => $this->faker->text(200), // Ограничиваем 200 символами
            'cover_url' => $this->faker->imageUrl(640, 480, 'book'),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'quantity' => $this->faker->numberBetween(0, 50),
        ];
    }
}
