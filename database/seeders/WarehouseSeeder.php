<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        Warehouse::create([
            'name'      => 'Almacén Principal',
            'short'     => 'Principal',
            'email'     => 'almacen@creador.com',
            'user_id'   => 2
        ]);
    }
}
