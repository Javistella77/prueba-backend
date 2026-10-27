<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
        ]);

        foreach ([['USD', 'US$', 1], ['EUR', '€', 0.92], ['ARS', '$', 1000]] as [$name, $symbol, $rate]) {
            Currency::create([
                'name' => $name,
                'symbol' => $symbol,
                'exchange_rate' => $rate,
            ]);
        }
    }
}