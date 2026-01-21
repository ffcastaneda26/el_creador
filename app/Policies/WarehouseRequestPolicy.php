<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WarehouseRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_warehouse::request');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WarehouseRequest $warehouseRequest): bool
    {
        if ($user->hasRole('Produccion') || $user->hasRole('Producción')) {
            return $warehouseRequest->user_id === $user->id;
        }
        return $user->can('view_warehouse::request')
            || $user->can('view_any_warehouse::request');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_warehouse::request');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WarehouseRequest $warehouseRequest): bool
    {
        if ($user->hasRole('Produccion') || $user->hasRole('Producción')) {
            return $warehouseRequest->user_id === $user->id;
        }
        return $user->can('update_warehouse::request');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WarehouseRequest $warehouseRequest): bool
    {
        if ($user->hasRole('Produccion') || $user->hasRole('Producción')) {
            return $warehouseRequest->user_id === $user->id;
        }
        return $user->can('delete_warehouse::request');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Produccion') || $user->hasRole('Producción')) {
            return false;
        }
        return $user->can('delete_any_warehouse::request');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, WarehouseRequest $warehouseRequest): bool
    {
        return $user->can('force_delete_warehouse::request');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_warehouse::request');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, WarehouseRequest $warehouseRequest): bool
    {
        return $user->can('restore_warehouse::request');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_warehouse::request');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, WarehouseRequest $warehouseRequest): bool
    {
        return $user->can('replicate_warehouse::request');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_warehouse::request');
    }
}
