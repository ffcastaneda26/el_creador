<?php

namespace App\Observers;

use App\Models\Movement;
use App\Helpers\InventoryManagement;

class MovementObserver
{
    /**
     * Handle the Movement "created" event.
     */
    public function created(Movement $movement): void
    {
        $warehouseId=$movement->warehouse_id;
        $productId=$movement->product_id;
        $keyMovementId= $movement->key_movement_id;
        $quantity = $movement->quantity;
        $cost= $movement->cost;
        InventoryManagement::updateStock($warehouseId,$productId,$keyMovementId,$quantity,$cost);
    }

    /**
     * Handle the Movement "updated" event.
     */
    public function updated(Movement $movement): void
    {
        //
    }


    /**
     * Handle the Movement "deleted" event.
     */
    public function deleted(Movement $movement): void
    {
        dd('Gestionar la actualización de Existencias y de Costo en caso de ser necesario');
        $warehouseId=$movement->warehouse_id;
        $productId=$movement->product_id;
        $keyMovementId= $movement->key_movement_id;

        $quantity = $movement->quantity;
        $cost= $movement->cost;
        InventoryManagement::updateStock($warehouseId,$productId,$keyMovementId,$quantity,$cost);

    }

    /**
     * Handle the Movement "restored" event.
     */
    public function restored(Movement $movement): void
    {
        //
    }

    /**
     * Handle the Movement "force deleted" event.
     */
    public function forceDeleted(Movement $movement): void
    {
        //
    }
}
