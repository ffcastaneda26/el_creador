<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'Super Admin',
            'Administrador',
            'Gerente',
            'Asesor',
            'Vendedor',
            'Capturista',
            'Produccion',
            'Envios',
            'Almacen',
            'Direccion',
        ];

        $roleModels = collect($roles)->mapWithKeys(function (string $roleName) {
            return [$roleName => Role::firstOrCreate(['name' => $roleName])];
        });

        $allPermissions = Permission::all();
        if ($allPermissions->isEmpty()) {
            return;
        }

        $resourcePrefixes = collect(config('filament-shield.permission_prefixes.resource', []))
            ->sortByDesc(fn (string $prefix) => strlen($prefix))
            ->values();

        $permissionPrefixFor = function (string $permissionName) use ($resourcePrefixes): ?string {
            foreach ($resourcePrefixes as $prefix) {
                if (Str::startsWith($permissionName, $prefix . '_')) {
                    return $prefix;
                }
            }
            return null;
        };

        $permissionsByResourceKey = $allPermissions
            ->filter(fn (Permission $permission) => $permissionPrefixFor($permission->name) !== null)
            ->groupBy(function (Permission $permission) use ($permissionPrefixFor): string {
                $prefix = $permissionPrefixFor($permission->name);
                return Str::after($permission->name, $prefix . '_');
            })
            ->map(fn (Collection $permissions) => $permissions->pluck('name')->values());

        $permissionsForResourceKeys = function (array $resourceKeys, ?array $allowedPrefixes = null) use ($permissionsByResourceKey, $permissionPrefixFor): Collection {
            $permissionNames = collect();
            foreach ($resourceKeys as $resourceKey) {
                $permissionNames = $permissionNames->merge($permissionsByResourceKey->get($resourceKey, collect()));
            }
            if ($allowedPrefixes === null) {
                return $permissionNames->unique()->values();
            }

            return $permissionNames->filter(function (string $permissionName) use ($allowedPrefixes, $permissionPrefixFor): bool {
                $prefix = $permissionPrefixFor($permissionName);
                return $prefix !== null && in_array($prefix, $allowedPrefixes, true);
            })->unique()->values();
        };

        $resourceKey = fn (string $resourceClass): string => Str::of(class_basename($resourceClass))
            ->beforeLast('Resource')
            ->snake('::')
            ->toString();

        $resourceKeys = [
            'client' => $resourceKey(\App\Filament\Resources\ClientResource::class),
            'cotization' => $resourceKey(\App\Filament\Asesor\Resources\CotizationResource::class),
            'order' => $resourceKey(\App\Filament\Asesor\Resources\OrderResource::class),
            'manufacturing' => $resourceKey(\App\Filament\Resources\ManufacturingResource::class),
            'warehouse' => $resourceKey(\App\Filament\Resources\WareHouseResource::class),
            'warehouse_request' => $resourceKey(\App\Filament\Resources\WarehouseRequestResource::class),
            'product_warehouse' => $resourceKey(\App\Filament\Resources\ProductWarehouseResource::class),
            'movement' => $resourceKey(\App\Filament\Resources\MovementResource::class),
            'key_movement' => $resourceKey(\App\Filament\Resources\KeyMovementResource::class),
            'role' => $resourceKey(\App\Filament\Resources\RoleResource::class),
            'permission' => $resourceKey(\App\Filament\Resources\PermissionResource::class),
            'user' => $resourceKey(\App\Filament\Resources\UserResource::class),
        ];

        $viewCreateUpdatePrefixes = ['view', 'view_any', 'create', 'update'];
        $viewUpdatePrefixes = ['view', 'view_any', 'update'];
        $viewPrefixes = ['view', 'view_any'];
        $viewCreatePrefixes = ['view', 'view_any', 'create'];

        $assign = function (string $roleName, Collection $permissionNames) use ($roleModels): void {
            $roleModels[$roleName]->syncPermissions($permissionNames->all());
        };

        $allPermissionNames = $allPermissions->pluck('name');

        $assign('Super Admin', $allPermissionNames);
        $assign('Administrador', $allPermissionNames);
        $assign('Direccion', $allPermissionNames);

        $managerExcludedResources = collect([
            $resourceKeys['role'],
            $resourceKeys['permission'],
            $resourceKeys['user'],
        ]);
        $managerPermissions = $allPermissionNames->reject(function (string $permissionName) use ($managerExcludedResources, $permissionPrefixFor): bool {
            $prefix = $permissionPrefixFor($permissionName);
            if ($prefix === null) {
                return false;
            }
            $resourceKey = Str::after($permissionName, $prefix . '_');
            return $managerExcludedResources->contains($resourceKey);
        });
        $assign('Gerente', $managerPermissions);

        $salesResourceKeys = [
            $resourceKeys['cotization'],
            $resourceKeys['order'],
            $resourceKeys['client'],
        ];
        $salesPermissions = $permissionsForResourceKeys($salesResourceKeys);
        $assign('Asesor', $salesPermissions);
        $assign('Vendedor', $salesPermissions);

        $capturistaPermissions = $permissionsForResourceKeys($salesResourceKeys, $viewCreateUpdatePrefixes);
        $assign('Capturista', $capturistaPermissions);

        $produccionPermissions = $permissionsForResourceKeys([$resourceKeys['manufacturing']], $viewUpdatePrefixes)
            ->merge($permissionsForResourceKeys([$resourceKeys['order']], $viewPrefixes))
            ->unique()
            ->values();
        $assign('Produccion', $produccionPermissions);

        $enviosPermissions = $permissionsForResourceKeys([$resourceKeys['order']], $viewPrefixes)
            ->merge($permissionsForResourceKeys([$resourceKeys['warehouse_request']], $viewUpdatePrefixes))
            ->merge($permissionsForResourceKeys([$resourceKeys['movement']], $viewCreatePrefixes))
            ->unique()
            ->values();
        $assign('Envios', $enviosPermissions);

        $almacenPermissions = $permissionsForResourceKeys([
            $resourceKeys['warehouse'],
            $resourceKeys['product_warehouse'],
            $resourceKeys['movement'],
            $resourceKeys['key_movement'],
        ])
            ->merge($permissionsForResourceKeys([$resourceKeys['warehouse_request']], $viewUpdatePrefixes))
            ->unique()
            ->values();
        $assign('Almacen', $almacenPermissions);
    }
}
