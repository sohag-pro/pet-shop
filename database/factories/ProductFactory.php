<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => CategoryFactory::new()->create(),
            'title' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'description' => [
                'en' => $this->faker->sentence(),
                'ar' => $this->faker->sentence(),
            ],
            'meta_data' => [
                'en' => $this->faker->sentence(),
                'ar' => $this->faker->sentence(),
            ],
        ];
    }
}
