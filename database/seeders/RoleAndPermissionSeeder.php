<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        // create permissions
        $permissions = [

        ];

        if(count($permissions)){
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }
        }

        // Super Admin con todos los permisos
        $role = Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
        $role = Role::create(['name' => 'Administrador']);
        $role = Role::create(['name' => 'Gerente']);
        $role = Role::create(['name' => 'Asesor']);
        $role = Role::create(['name' => 'Vendedor']);
        $role = Role::create(['name' => 'Capturista']);
        $role = Role::create(['name' => 'Producción']);
        $role = Role::create(['name' => 'Envíos']);
    }
}
