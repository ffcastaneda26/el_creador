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

        $sql = "INSERT INTO coverages (name,distance,fee) VALUES
            ('Mex-Mex',0,0),
            ('Mex/Mex2',0,0),
            ('A',110,0),
            ('B',370,0),
            ('C',515,0),
            ('D',920,0),
            ('E',1500,0),
            ('F',2000,0),
            ('G',2300,0),
            ('H',2900,0);";
        DB::update($sql);
        $this->command->info('Taba de Coberturas Territoriales Creada');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $this->call(DetailCoverageSeeder::class);
    }
}
