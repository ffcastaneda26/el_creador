<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        // User::factory()->withPersonalTeam()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->truncateTables([
            'user_roles',
            'role_permissions',
            'user_permissions',
            'users',
            'roles',
            'permissions',
            'countries',
            'states',
            'cities',
            'type_zipcodes',
            'units',
            'detail_parts',
            'parts',
            'anexos',
            'warehouses',
            'key_movements',
        ]);

        $this->call(RoleAndPermissionSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(MuniciaplitiesSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(TypeZipcodeSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(KeyMovementSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(PartSeeder::class);
        $this->call(AnexoSeeder::class);
        $this->call(CoverageSeeder::class);

    }

    protected function truncateTables(array $tables) {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;'); // Desactivamos la revisión de claves foráneas
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;'); // Desactivamos la revisión de claves foráneas
    }
}
