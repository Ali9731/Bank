<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use App\Models\TransactionSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        foreach ($users as $user) {
            $accounts = Account::factory(2)->for($user)->create();
            foreach ($accounts as $account) {
                Card::factory(3)->for($account)->create(['user_id' => $user->id]);
            }
        }
        TransactionSetting::factory()->create([
            'commission' => 500,
            'min_transaction' => 1000,
            'max_transaction' => 50000000,
        ]);
    }
}
