<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Creando Tabla de Coberturas Territoriales');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('detail_coverages')->truncate();
        DB::table('coverages')->truncate();

        $sql = "INSERT INTO coverages (name,distance) VALUES
            ('Mex-Mex',0),
            ('Mex/Mex2',0),
            ('A',110),
            ('B',370),
            ('C',515),
            ('D',920),
            ('E',1500),
            ('F',2000),
            ('G',2300),
            ('H',2900);";
        DB::update($sql);
        $this->command->info('Taba de Coberturas Territoriales Creada');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
