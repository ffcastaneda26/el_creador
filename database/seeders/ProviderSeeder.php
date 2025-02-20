<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Creando Tabla de Proveedores');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('providers')->truncate();

        $totalProviders = 15; // Número total de proveedores a crear
        $bar = $this->command->getOutput()->createProgressBar($totalProviders); // Crea la barra de progreso
        $bar->start(); // Inicia la barra

        // Provider::factory($totalProviders)->create()->each(function () use ($bar) {
        //     $bar->advance(); // Avanza la barra por cada proveedor creado
        // });
        for ($i = 0; $i < $totalProviders; $i++) {
            Provider::factory()->create(); // Crea un solo proveedor en cada iteración
            $bar->advance(); // Avanza la barra
        }


        $bar->finish(); // Finaliza la barra
        $this->command->newLine(2); // Dos líneas en blanco después de la barra

        $this->command->info('Tabla de Proveedores Creada');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
