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
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roleNames = [
            'Super Admin',
            'super_admin',
            'Dueno CEO',
            'Direccion',
            'Administrador',
            'Administrador Contador',
            'Gerente',
            'Director Ventas',
            'Gerente Ventas',
            'Asesor',
            'Vendedor',
            'Capturista',
            'Produccion',
            'Director Produccion',
            'Gerente Produccion',
            'Operativo Produccion',
            'Almacen',
            'Gerente CAE',
            'Envios',
            'Chofer Entrega',
        ];

        $roleModels = collect($roleNames)->mapWithKeys(function (string $roleName): array {
            return [
                $roleName => Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]),
            ];
        });

        $customPermissions = [
            'view_profit_reports',
            'view_payroll',
            'manage_payroll',
            'audit_inventory_logs',
            'approve_extra_production',
            'authorize_warranty_requests',
            'upload_payment_proof',
            'mark_delivery_completed',
            'edit_general_calendar_dates',
            'view_purchase_costs',
            'view_sales_amounts',
            'approve_financial_release',
            'activate_manufacturing_after_payment',
            'justify_inventory_loss',
        ];

        foreach ($customPermissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $allPermissions = Permission::query()->get();
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
            ->filter(fn (Permission $permission): bool => $permissionPrefixFor($permission->name) !== null)
            ->groupBy(function (Permission $permission) use ($permissionPrefixFor): string {
                $prefix = $permissionPrefixFor($permission->name);

                return Str::after($permission->name, $prefix . '_');
            })
            ->map(fn (Collection $permissions): Collection => $permissions->pluck('name')->values());

        $permissionsForResources = function (array $resourceKeys, ?array $allowedPrefixes = null) use ($permissionsByResourceKey, $permissionPrefixFor): Collection {
            $permissionNames = collect();

            foreach ($resourceKeys as $resourceKey) {
                $permissionNames = $permissionNames->merge($permissionsByResourceKey->get($resourceKey, collect()));
            }

            if ($allowedPrefixes === null) {
                return $permissionNames->unique()->values();
            }

            return $permissionNames
                ->filter(function (string $permissionName) use ($allowedPrefixes, $permissionPrefixFor): bool {
                    $prefix = $permissionPrefixFor($permissionName);

                    return $prefix !== null && in_array($prefix, $allowedPrefixes, true);
                })
                ->unique()
                ->values();
        };

        $permissionsForCustom = fn (array $permissionNames): Collection => Permission::query()
            ->whereIn('name', $permissionNames)
            ->pluck('name');

        $resourceKey = fn (string $resourceClass): string => Str::of(class_basename($resourceClass))
            ->beforeLast('Resource')
            ->snake('::')
            ->toString();

        $resourceKeys = [
            'client' => $resourceKey(\App\Filament\Resources\ClientResource::class),
            'cotization' => $resourceKey(\App\Filament\Asesor\Resources\CotizationResource::class),
            'order' => $resourceKey(\App\Filament\Asesor\Resources\OrderResource::class),
            'event' => $resourceKey(\App\Filament\Resources\EventResource::class),
            'manufacturing' => $resourceKey(\App\Filament\Resources\ManufacturingResource::class),
            'warehouse' => $resourceKey(\App\Filament\Resources\WareHouseResource::class),
            'warehouse_request' => $resourceKey(\App\Filament\Resources\WarehouseRequestResource::class),
            'product_warehouse' => $resourceKey(\App\Filament\Resources\ProductWarehouseResource::class),
            'movement' => $resourceKey(\App\Filament\Resources\MovementResource::class),
            'key_movement' => $resourceKey(\App\Filament\Resources\KeyMovementResource::class),
            'provider' => $resourceKey(\App\Filament\Resources\ProviderResource::class),
            'purchase' => $resourceKey(\App\Filament\Resources\PurchaseResource::class),
            'payment' => $resourceKey(\App\Filament\Resources\PaymentResource::class),
            'payment_method' => $resourceKey(\App\Filament\Resources\PaymentMethodResource::class),
            'user' => $resourceKey(\App\Filament\Resources\UserResource::class),
        ];

        $readPrefixes = ['view', 'view_any'];
        $readWritePrefixes = ['view', 'view_any', 'create', 'update'];
        $managementPrefixes = ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];

        $fullAccessPermissions = Permission::query()->pluck('name');

        $adminContadorPermissions = collect()
            ->merge($permissionsForResources([
                $resourceKeys['payment'],
                $resourceKeys['payment_method'],
                $resourceKeys['purchase'],
            ], $managementPrefixes))
            ->merge($permissionsForResources([$resourceKeys['order']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['event']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['manufacturing']], $readPrefixes))
            ->merge($permissionsForResources([
                $resourceKeys['warehouse'],
                $resourceKeys['product_warehouse'],
                $resourceKeys['movement'],
                $resourceKeys['provider'],
            ], $readPrefixes))
            ->merge($permissionsForResources([$resourceKeys['user']], $readPrefixes))
            ->merge($permissionsForCustom([
                'view_profit_reports',
                'view_payroll',
                'manage_payroll',
                'approve_financial_release',
                'activate_manufacturing_after_payment',
                'approve_extra_production',
            ]))
            ->unique()
            ->values();

        $ventasLiderPermissions = collect()
            ->merge($permissionsForResources([
                $resourceKeys['cotization'],
                $resourceKeys['order'],
                $resourceKeys['client'],
            ], $readWritePrefixes))
            ->merge($permissionsForResources([$resourceKeys['event']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['manufacturing']], $readPrefixes))
            ->merge($permissionsForCustom([
                'authorize_warranty_requests',
                'edit_general_calendar_dates',
            ]))
            ->unique()
            ->values();

        $vendedorPermissions = collect()
            ->merge($permissionsForResources([
                $resourceKeys['cotization'],
                $resourceKeys['order'],
                $resourceKeys['client'],
            ], $readWritePrefixes))
            ->merge($permissionsForResources([
                $resourceKeys['event'],
                $resourceKeys['product_warehouse'],
                $resourceKeys['warehouse'],
            ], $readPrefixes))
            ->merge($permissionsForCustom([
                'upload_payment_proof',
                'authorize_warranty_requests',
            ]))
            ->unique()
            ->values();

        $capturistaPermissions = collect()
            ->merge($permissionsForResources([
                $resourceKeys['cotization'],
                $resourceKeys['order'],
                $resourceKeys['client'],
            ], $readWritePrefixes))
            ->merge($permissionsForResources([$resourceKeys['event']], $readPrefixes))
            ->unique()
            ->values();

        $produccionLiderPermissions = collect()
            ->merge($permissionsForResources([
                $resourceKeys['manufacturing'],
                $resourceKeys['warehouse_request'],
            ], $readWritePrefixes))
            ->merge($permissionsForResources([$resourceKeys['event']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['order']], $readPrefixes))
            ->merge($permissionsForCustom([
                'approve_extra_production',
            ]))
            ->unique()
            ->values();

        $produccionOperativoPermissions = collect()
            ->merge($permissionsForResources([$resourceKeys['manufacturing']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['event'], $resourceKeys['order']], $readPrefixes))
            ->unique()
            ->values();

        $gerenteCaePermissions = collect()
            ->merge($permissionsForResources([
                $resourceKeys['warehouse'],
                $resourceKeys['product_warehouse'],
                $resourceKeys['movement'],
                $resourceKeys['key_movement'],
                $resourceKeys['provider'],
            ], $managementPrefixes))
            ->merge($permissionsForResources([$resourceKeys['purchase']], $readWritePrefixes))
            ->merge($permissionsForResources([$resourceKeys['warehouse_request']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['manufacturing']], $readPrefixes))
            ->merge($permissionsForCustom([
                'justify_inventory_loss',
                'audit_inventory_logs',
                'view_purchase_costs',
            ]))
            ->unique()
            ->values();

        $choferPermissions = collect()
            ->merge($permissionsForResources([$resourceKeys['order']], ['view', 'view_any', 'update']))
            ->merge($permissionsForResources([$resourceKeys['event']], $readPrefixes))
            ->merge($permissionsForCustom([
                'mark_delivery_completed',
            ]))
            ->unique()
            ->values();

        $assign = function (string $roleName, Collection $permissionNames) use ($roleModels): void {
            $roleModels[$roleName]->syncPermissions($permissionNames->all());
        };

        $assign('Super Admin', $fullAccessPermissions);
        $assign('super_admin', $fullAccessPermissions);
        $assign('Dueno CEO', $fullAccessPermissions);
        $assign('Direccion', $fullAccessPermissions);

        $assign('Administrador', $adminContadorPermissions);
        $assign('Administrador Contador', $adminContadorPermissions);

        $assign('Gerente', $ventasLiderPermissions);
        $assign('Director Ventas', $ventasLiderPermissions);
        $assign('Gerente Ventas', $ventasLiderPermissions);
        $assign('Asesor', $ventasLiderPermissions);

        $assign('Vendedor', $vendedorPermissions);
        $assign('Capturista', $capturistaPermissions);

        $assign('Produccion', $produccionLiderPermissions);
        $assign('Director Produccion', $produccionLiderPermissions);
        $assign('Gerente Produccion', $produccionLiderPermissions);
        $assign('Operativo Produccion', $produccionOperativoPermissions);

        $assign('Almacen', $gerenteCaePermissions);
        $assign('Gerente CAE', $gerenteCaePermissions);

        $assign('Envios', $choferPermissions);
        $assign('Chofer Entrega', $choferPermissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

