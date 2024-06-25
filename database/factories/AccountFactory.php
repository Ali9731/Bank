<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Core\Number;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $number = new Number();

        return [
            'user_id' => User::factory(),
            'account_number' => $number->numberBetween(10000000, 99999999),
            'amount' => $number->numberBetween(100000, 1000000),
        ];
    }
}
