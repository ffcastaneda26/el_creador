<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::factory()
            ->count(3)
            ->create([
                'country_id' => 135,
                'state_id' => 8,
                'municipality_id' => 217,
                'city_id' => 250,
            ]);
    }
}
