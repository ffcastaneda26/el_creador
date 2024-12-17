<?php

namespace App\Helpers;
use App\Models\ProductWarehouse;
use App\Models\KeyMovement;

class InventoryManagement
{
    static public function updateStock($warehouseId,$productId, $keyMovementId, $quantity, $cost = 0)
    {
        $product = ProductWarehouse::where('warehouse_id',$warehouseId)
                                ->where('product_id',$productId)
                                ->first();

        $keyMovement = KeyMovement::find($keyMovementId);

        $require_cost = $keyMovement->require_cost;
        $current_stock = $product->stock;
        $current_cost = $product->average_cost;
        $product->stock = $keyMovement->isTypeI() ? $product->stock + $quantity : $product->stock - $quantity;
        $product->stock_available = $keyMovement->isTypeI() ? $product->stock_available + $quantity : $product->stock_available - $quantity;
        
        $product->save();

        if ($require_cost && $cost > 0) {
                $product->average_cost = Self::calculateAverageCost($current_stock, $current_cost, $quantity, $cost);
                $product->save();
        }

    }

    static public function calculateAverageCost($currentStock, $currentAverageCost, $quantity, $cost)
    {
        $newTotalCost = ($currentStock * $currentAverageCost) + ($quantity * $cost);
        $newStock = $currentStock + $quantity;
        $newAverageCost = $newTotalCost / $newStock;
        return $newAverageCost;
    }

}
