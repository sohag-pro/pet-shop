<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new()->create(),
            'order_status_id' => OrderStatusFactory::new()->create(),
            'payment_id' => PaymentFactory::new()->create(),
            'products' => [
                [
                    'id' => ProductFactory::new()->create(),
                    'quantity' => $this->faker->numberBetween(1, 10),
                ],
                [
                    'id' => ProductFactory::new()->create(),
                    'quantity' => $this->faker->numberBetween(1, 10),
                ],
            ],
            'address' => [
                'address' => $this->faker->address(),
                'city' => $this->faker->city(),
                'country' => $this->faker->country(),
                'postal_code' => $this->faker->postcode(),
            ],
            'amount' => $this->faker->randomFloat(2, 0, 1000),
            'shipped_at' => $this->faker->dateTime(),
        ];
    }
}
