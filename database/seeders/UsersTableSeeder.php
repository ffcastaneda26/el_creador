<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Administrador",
            "email" => "administrador@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Administrador');

        User::create([
            "name" => "Gerente",
            "email" => "gerente@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Gerente');

        User::create([
            "name" => "Asesor",
            "email" => "asesor@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Asesor');

        User::create([
            "name" => "Vendedor",
            "email" => "vendedor@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Vendedor');

        User::create([
            "name" => "Capturista",
            "email" => "capturista@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Capturista');

        User::create([
            "name" => "Producción",
            "email" => "produccion@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Producción');

        User::create([
            "name" => "Envios",
            "email" => "envios@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Envios');
        User::create([
            "name" => "Almacen",
            "email" => "almacen@creador.com",
            "password" => bcrypt("password"),
            "active" => 1,
        ])->assignRole('Almacén');
    }
}
