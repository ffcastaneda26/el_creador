<?php

namespace App\Observers;

use App\Models\Movement;
use App\Helpers\InventoryManagement;

class MovementObserver
{

    public function created(Movement $movement): void
    {
        InventoryManagement::updateStock($movement,'normal');
        InventoryManagement::calculateAverageCost($movement,'normal');
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
        $product->last_purchase_price = round($movement->quantity * $movement->cost,6);
        $product->save();
    }
}
