<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $cardDetails = [
            'card_number' => $this->faker->creditCardNumber(),
            'card_holder_name' => $this->faker->name(),
            'card_expiration_month' => $this->faker->numberBetween(1, 12),
            'card_expiration_year' => $this->faker->numberBetween(2021, 2030),
            'card_cvv' => $this->faker->numberBetween(100, 999),
        ];

        $cashDetails = [
            'cash_on_delivery' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
        ];

        $bankDetails = [
            'bank_name' => $this->faker->word(),
            'account_number' => $this->faker->bankAccountNumber(),
            'iban' => $this->faker->iban(),
        ];

        $type = $this->faker->randomElement(['cash', 'card', 'bank']);

        $details = match ($type) {
            'card' => $cardDetails,
            'cash' => $cashDetails,
            'bank' => $bankDetails,
        };

        return [
            'type' => $type,
            'details' => $details,
        ];
    }
}
