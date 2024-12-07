<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PartesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('partes')->truncate();
        $sql= "INSERT INTO partes VALUES
            (1,'Rostro',0,0),
            (2,'Cejas',0,0),
            (3,'Pestañas',0,0),
            (4,'Parpado',0,0),
            (5,'Pupila',0,0),
            (6,'Iris',0,0),
            (7,'Esclerotica',0,0),
            (8,'Brillo',0,0),
            (9,'Nariz',0,0),
            (10,'Fosas',0,0),
            (11,'Cuello',0,0),
            (12,'Pecho',0,0),
            (13,'Brazo',0,0),
            (14,'Muñeca',0,0),
            (15,'Palma',0,0),
            (16,'Dedos',0,0),
            (17,'Mano',0,0),
            (18,'Uñas/Garras',0,0),
            (19,'Abdomen',0,0),
            (20,'Espalda',0,0),
            (21,'Antebrazo',0,0),
            (22,'Gluteos',0,0),
            (23,'Muslo',0,0),
            (24,'Pierna',0,0),
            (25,'Pantorrila',0,0);";
        DB::update($sql);
    }
}
