<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionSetting>
 */
class TransactionSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commission' => fake()->numberBetween(1, 1000),
            'min_transaction' => fake()->numberBetween(1000, 10000),
            'max_transaction' => fake()->numberBetween(50000000, 60000000),
        ];
    }
}
