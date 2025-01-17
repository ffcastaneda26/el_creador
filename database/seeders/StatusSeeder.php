<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $sql= "INSERT INTO units VALUES
                (1,'Pieza','pza','Cantidad','Pieza'),
                (2,'Juego','jgo','Cantidad','Juego'),
                (3,'Blister','blis','Cantidad','Blister'),
                (4,'Centímetro','cm','Longitud','Submúltiplo del metro.'),
                (5,'Kilómetro','km','Longitud','Múltiplo del metro.'),
                (6,'Pulgada','in','Longitud','Unidad de medida anglosajona.'),
                (7,'Kilogramo','kg','Masa','Unidad fundamental del SI para medir masa.'),
                (8,'Gramo','g','Masa','Submúltiplo del kilogramo.'),
                (9,'Tonelada','t','Masa','Múltiplo del kilogramo.'),
                (10,'Libra','lb','Masa','Unidad de medida anglosajona.'),
                (11,'Onza','oz','Masa','Unidad de medida anglosajona.'),
                (12,'Watt','W','Potencia','Unidad de medida de potencia.'),
                (13,'Pascal','Pa','Presión','Unidad de medida de presión.'),
                (14,'Grado Celsius','°C','Temperatura','Escala de temperatura centígrada.'),
                (15,'Kelvin','K','Temperatura','Unidad fundamental del SI para medir temperatura.'),
                (16,'Fahrenheit','°F','Temperatura','Escala de temperatura anglosajona.'),
                (17,'Segundo','s','Tiempo','Unidad fundamental del SI para medir tiempo.'),
                (18,'Minuto','min','Tiempo','Submúltiplo del segundo.'),
                (19,'Hora','h','Tiempo','Submúltiplo del segundo.'),
                (20,'Día','d','Tiempo','Unidad de tiempo basada en la rotación de la Tierra.'),
                (21,'Volt','V','Voltaje','Unidad de medida de diferencia de potencial eléctrico.'),
                (22,'Litro','L','Volumen','Unidad de medida para líquidos.'),
                (23,'Mililitro','ml','Volumen','Submúltiplo del litro.'),
                (24,'Galón','gal','Volumen','Unidad de medida anglosajona.'),
                (25,'Metro Cuadrado','m2','Area','Metro cuadrado de algo.'),
                (26,'Pie','ft','Longitud','Unidad de medida anglosajona.'),
                (27,'Yarda','yd','Longitud','Unidad de medida anglosajona.'),
                (28,'Metro','m','Longitud','Unidad fundamental del SI para medir distancias.');";

            DB::update($sql);

    }
}
