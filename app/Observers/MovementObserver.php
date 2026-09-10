<?php

namespace App\Observers;

use App\Models\Movement;
use App\Helpers\InventoryManagement;

class MovementObserver
{

    public function created(Movement $movement): void
    {
        InventoryManagement::updateStock($movement,'normal');
        if($movement->key_movement->is_purchase){
            $this->setLastPurchasePrice($movement);
        }
    }

    public function updated(Movement $movement)
    {
        InventoryManagement::updateStock($movement,'normal');
    }

    public function deleted(Movement $movement): void
    {
        InventoryManagement::updateStock($movement,'delete');
    }

    private  function setLastPurchasePrice($movement)
    {
        $product = InventoryManagement::getProduct($movement);
        // Es el precio UNITARIO de la ultima compra, no el importe de la partida.
        $product->last_purchase_price = round($movement->cost, 6);
        $product->save();
    }
}
