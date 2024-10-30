<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(__('Creating') . ' ' . __('States Table'));

        DB::table('states')->truncate();

        $sql="INSERT INTO `states` VALUES
            (1, 'Aguascalientes', 'Ags', 135),
            (2, 'Baja California', 'BC', 135),
            (3, 'Baja California Sur', 'BCS', 135),
            (4, 'Campeche', 'Camp', 135),
            (5, 'Coahuila de Zaragoza', 'Coah', 135),
            (6, 'Colima', 'Col', 135),
            (7, 'Chiapas', 'Chis', 135),
            (8, 'Chihuahua', 'Chih', 135),
            (9, 'Ciudad de México', 'Cdmx', 135),
            (10, 'Durango', 'Dgo', 135),
            (11, 'Guanajuato', 'Gto', 135),
            (12, 'Guerrero', 'Gro', 135),
            (13, 'Hidalgo', 'Hgo', 135),
            (14, 'Jalisco', 'Jal', 135),
            (15, 'México', 'Mex', 135),
            (16, 'Michoacán de Ocampo', 'Mich', 135),
            (17, 'Morelos', 'Mor', 135),
            (18, 'Nayarit', 'Nay', 135),
            (19, 'Nuevo León', 'NL', 135),
            (20, 'Oaxaca', 'Oax', 135),
            (21, 'Puebla', 'Pue', 135),
            (22, 'Querétaro', 'Qro', 135),
            (23, 'Quintana Roo', 'Qroo', 135),
            (24, 'San Luis Potosí', 'SLP', 135),
            (25, 'Sinaloa', 'Sin', 135),
            (26, 'Sonora', 'Son', 135),
            (27, 'Tabasco', 'Tab', 135),
            (28, 'Tamaulipas', 'Tamps', 135),
            (29, 'Tlaxcala', 'Tlax', 135),
            (30, 'Veracruz de Ignacio de la Llav', 'Ver', 135),
            (31, 'Yucatán', 'Yuc', 135),
            (32, 'Zacatecas', 'Zac', 135)";
        DB::update($sql);

       $this->command->info(__('States Table') . ' ' . __('Created'));

    }
}
