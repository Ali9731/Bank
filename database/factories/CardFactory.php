<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cardNumber = fake()->creditCardNumber();
        //this loop is dangerous I know, but the fake credit card sometimes returns card numbers with less than 16 digits. just a simple way to save time :)
        while (strlen($cardNumber) != 16){
            $cardNumber = fake()->creditCardNumber();
        }
        return [
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'card_number' => $cardNumber,
        ];
    }
}
