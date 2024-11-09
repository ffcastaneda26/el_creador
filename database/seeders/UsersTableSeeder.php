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
            "name"      => "Administrador",
            "email"     => "administrador@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Administrador');

        User::create([
            "name"      => "Gerente",
            "email"     => "gerente@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Gerente');

        User::create([
            "name"      => "Asesor",
            "email"     => "asesor@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Asesor');

        User::create([
            "name"      => "Vendedor",
            "email"     => "vendedor@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Vendedor');

        User::create([
            "name"      => "Capturista",
            "email"     => "capturista@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Capturista');

        User::create([
            "name"      => "Producción",
            "email"     => "produccion@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Producción');

        User::create([
            "name"      => "Envios",
            "email"     => "envios@creador.com",
            "password"  => bcrypt("password"),
        ])->assignRole('Envios');
    }
}
