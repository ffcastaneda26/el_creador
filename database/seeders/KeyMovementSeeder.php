<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KeyMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Creando Claves de Movimiento');

        DB::table('key_movements')->truncate();
            $sql = "INSERT INTO key_movements VALUES
            (1,'Compra','Comp','I', 'I', 1,2),
            (2,'Devolución Cliente','DevCte','I','I',0,2),
            (3,'Ajuste Entrada','AjuEnt','I','I',0,2),
            (4,'Venta','Venta','I','O',0,2),
            (5,'Devolución Proveedor','DevPro','I','O',0,2),
            (6,'Ajuste Salida','AjuSal','I','O',0,2);";

        DB::update($sql);

        $this->command->info('Claves de Movimiento Creadas');
    }
}
