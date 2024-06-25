<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
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
         User::factory(10)->has(Account::factory(2)->has(Card::factory(3)))->create();
    }
}
