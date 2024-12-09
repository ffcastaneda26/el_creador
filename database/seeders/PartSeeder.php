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
        DB::table('parts')->truncate();
        $sql= "INSERT INTO parts VALUES
            (1,'Cabeza'),
            (2,'Cuerpo'),
            (3,'Body Interno'),
            (4,'Out Fit 1'),
            (5,'Out Fit 2'),
            (6,'Zapatos'),
            (7,'Accesorios'),
            (8,'Objeto'),
            (9,'Rostro'),
            (10,'Cejas'),
            (11,'Pestañas'),
            (12,'Parpado'),
            (13,'Pupila'),
            (14,'Iris'),
            (15,'Esclerotica'),
            (16,'Brillo'),
            (17,'Nariz'),
            (18,'Fosas'),
            (19,'Labios'),
            (20,'Boca'),
            (21,'Dientes'),
            (22,'Barba'),
            (23,'Bigote'),
            (24,'Lengua'),
            (25,'Cabello'),
            (26,'Orejas'),
            (27,'Lentes'),
            (28,'Modelo'),
            (29,'Estatura'),
            (30,'Forma'),
            (31,'Espuma'),
            (32,'Polyfoam'),
            (33,'Full'),
            (34,'Print'),
            (35,'Gluteos'),
            (36,'Guantes'),
            (37,'Dedos'),
            (38,'Panza'),
            (39,'Cuello'),
            (40,'Pecho'),
            (41,'Brazo'),
            (42,'Muñeca'),
            (43,'Palma'),
            (44,'Mano'),
            (45,'Uñas/Garras'),
            (46,'Abdomen'),
            (47,'Espalda'),
            (48,'Antebrazo'),
            (49,'Muslo'),
            (50,'Pierna'),
            (51,'Pantorrila'),
            (52,'Relleno'),
            (53,'Completo'),
            (54,'Color'),
            (55,'Ubicacion'),
            (56,'Pantorrilla'),
            (57,'Camisa'),
            (58,'Saco'),
            (59,'Panalón'),
            (60,'Calcetas'),
            (61,'Talla'),
            (62,'Full Print'),
            (63,'Playera'),
            (64,'Short'),
            (65,'Pans'),
            (66,'Parte Alta'),
            (67,'Parte Media'),
            (68,'Parte Baja'),
            (69,'Izquierda'),
            (70,'Derecha'),
            (71,'Frontal'),
            (72,'Atras'),
            (73,'Franjas'),
            (74,'Suela'),
            (75,'Entre Suela'),
            (76,'Lengueta'),
            (77,'Ojillos'),
            (78,'Agujeta'),
            (79,'Gorra'),
            (80,'Aretes'),
            (81,'Estetoscopio'),
            (82,'Mochila'),
            (83,'Bandera'),
            (84,'Paliacate'),
            (85,'Reloj'),
            (86,'Guitarra');";
        DB::update($sql);
    }
}
