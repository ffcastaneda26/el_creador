<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('detail_parts')->truncate();
        DB::table('parts')->truncate();
        $sql = "INSERT INTO parts (`name`) VALUES
            ('Cabeza'),
            ('Cuerpo'),
            ('Estructura'),
            ('Body Interno'),
            ('Out Fit 1'),
            ('Out Fit 2'),
            ('Zapatos'),
            ('Accesorios'),
            ('Objeto'),
            ('Tamaño'),
            ('Fibra'),
            ('SV'),
            ('Pila'),
            ('Cargador'),
            ('Rostro'),
            ('Cejas'),
            ('Pestañas'),
            ('Parpado'),
            ('Pupila'),
            ('Iris'),
            ('Esclerotica'),
            ('Brillo'),
            ('Nariz'),
            ('Fosas'),
            ('Labios'),
            ('Boca'),
            ('Dientes'),
            ('Barba'),
            ('Bigote'),
            ('Lengua'),
            ('Cabello'),
            ('Orejas'),
            ('Lentes'),
            ('Modelo'),
            ('Estatura'),
            ('Forma'),
            ('Espuma'),
            ('Polyfoam'),
            ('Full'),
            ('Print'),
            ('Gluteos'),
            ('Guantes'),
            ('Dedos'),
            ('Panza'),
            ('Cuello'),
            ('Pecho'),
            ('Brazo'),
            ('Muñeca'),
            ('Palma'),
            ('Mano'),
            ('Uñas/Garras'),
            ('Abdomen'),
            ('Espalda'),
            ('Antebrazo'),
            ('Muslo'),
            ('Pierna'),
            ('Pantorrila'),
            ('Relleno'),
            ('Completo'),
            ('Color'),
            ('Ubicacion'),
            ('Pantorrilla'),
            ('Camisa'),
            ('Saco'),
            ('Pantalón'),
            ('Calcetas'),
            ('Talla'),
            ('Full Print'),
            ('Playera'),
            ('Short'),
            ('Pans'),
            ('Parte Alta'),
            ('Parte Media'),
            ('Parte Baja'),
            ('Izquierda'),
            ('Derecha'),
            ('Frontal'),
            ('Atras'),
            ('Franjas'),
            ('Suela'),
            ('Entre Suela'),
            ('Lengueta'),
            ('Ojillos'),
            ('Agujeta'),
            ('Gorra'),
            ('Aretes'),
            ('Estetoscopio'),
            ('Mochila'),
            ('Bandera'),
            ('Paliacate'),
            ('Reloj'),
            ('Guitarra');";
        DB::update($sql);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
