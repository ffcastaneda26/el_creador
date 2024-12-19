<?php

namespace App\Observers;

use App\Models\Movement;
use App\Helpers\InventoryManagement;

class MovementObserver
{


    public function created(Movement $movement): void
    {
        InventoryManagement::updateStock($movement,'normal');
    }


    
    public function updated(Movement $movement)
    {
        // InventoryManagement::updateStock($movement,'normal');
    }

    public function deleted(Movement $movement): void
    {
        InventoryManagement::updateStock($movement,'delete');
    }

}
