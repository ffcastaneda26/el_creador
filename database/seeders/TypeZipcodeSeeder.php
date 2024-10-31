<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeZipcodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(__('Creating') . ' ' . __('Zipcode Types Table'));

        $sql="INSERT INTO type_zipcodes VALUES
        (1,'Colonia'),
        (2,'Barrio'),
        (3,'Fraccionamiento'),
        (4,'Unidad habitacional'),
        (5,'Residencial'),
        (6,'Conjunto habitacional'),
        (7,'Zona comercial'),
        (8,'Aeropuerto'),
        (9,'Villa'),
        (10,'Ejido'),
        (11,'Pueblo'),
        (12,'Condominio'),
        (13,'Campamento'),
        (14,'Equipamiento'),
        (15,'Club de golf'),
        (16,'Gran usuario'),
        (17,'Ranchería'),
        (18,'Parque industrial'),
        (19,'Granja'),
        (20,'Zona industrial'),
        (21,'Ampliación'),
        (22,'Hacienda'),
        (23,'Rancho'),
        (24,'Exhacienda'),
        (25,'Paraje'),
        (26,'Ingenio'),
        (27,'Zona militar'),
        (28,'Zona federal'),
        (29,'Puerto'),
        (30,'Finca'),
        (31,'Poblado comunal'),
        (32,'Congregación'),
        (33,'Estación'),
        (34,'Ciudad');";
        DB::update($sql);
        $this->command->info(__('Zipcode Types Table') . ' ' . __('Created'));
    }
}
