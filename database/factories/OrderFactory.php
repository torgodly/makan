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
            'order_number' => $this->faker->unique()->randomNumber(8),
            'customer_id' => \App\Models\Customer::factory(),
            'status' => $this->faker->randomElement(['Open', 'Finished']),
            'user_id' => \App\Models\User::factory(),
            'payment_method' => $this->faker->randomElement(['Cash', 'Credit Card', 'Bank Transfer']),
            'note' => $this->faker->text,
            'discount' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
