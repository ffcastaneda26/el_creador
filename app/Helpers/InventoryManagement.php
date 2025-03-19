<?php

namespace App\Helpers;
use App\Models\Movement;
use App\Models\KeyMovement;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryManagement
{
    static public function updateStock($movement,$type='normal')
    {

        try {
            $product = self::getProduct($movement);

            if ($movement->key_movement->require_cost) {
                $product->average_cost = self::calculateAverageCost($product,$movement,$type);
            }

            $newStock = self::calculateNewStock($movement->key_movement,$product->stock,$movement->quantity,$type);
            $product->stock           = $newStock;
            $product->stock_available = $product->stock - $product->stock_compromised;

            if ($movement->key_movement->is_purchase) {

                $product->last_purchase_price =  $type == 'normal' ? $movement->cost
                                                                   : self::getLastPurchasePrice($movement);
            }

            $product->save();

        } catch (\Throwable $th) {
            Log::info("Movimientos de almacén  " . $th->getMessage());
            return false;
        }
    }

    static private function calculateLastPurchasePrice($product,$movement,$type='normal'){
        if($type == 'normal'){
            if ($movement->key_movement->is_purchase ) {
                return  $movement->cost;
            }
        }
    }
    static private function   calculateNewStock($key_movement,$currentStock,$quantity,$type='normal'){
        $isTypeInput = $key_movement->isTypeInput();

        if($type == 'normal'){
           return  $isTypeInput ? $currentStock + $quantity : $currentStock - $quantity;
        }
        if($type =='delete'){
            return $isTypeInput ? $currentStock - $quantity : $currentStock + $quantity;
        }

    }
    static public function calculateAverageCost($movement,$type='normal')
    {
        $product = self::getProduct($movement);
        $currentTotalCost = self::getTotalCost($product->stock,$product->average_cost);
        $amountMovement = self::getAmountMovement($movement->quantity,$movement->cost);

        if($type == 'normal'){
            $newStock = self::getNewStock($product->stock,$movement->quantity);
            return ( $currentTotalCost + $amountMovement ) / $newStock;
        }
        if($type == 'delete'){
            $newStock = self::getNewStock($product->stock,$movement->quantity*-1);
            return ( $currentTotalCost - $amountMovement ) / $newStock;
        }
    }

    static public function getTotalCost($stock,$average_cost){
        return $stock * $average_cost;
    }

    static public function getAmountMovement($quantity,$cost){
        return $quantity * $cost;
    }

    static public function getNewStock($currentStock,$quantity){
        return $currentStock + $quantity;
    }
    static public function show_values($currentStock, $currentAverageCost, $amountMovement, $cost,$type='normal',$newStock,$newAverageCost)
    {

        dd('Cálculo proceso=' . $type,
            'Existencia actual=' . $currentStock,
            'Costo Actual=' . $currentAverageCost,
            'Importe=' . $amountMovement,
            'Costo Movimiento=' . $cost,
            'Nueva Existencia=' .$newStock,
            'Costo Nuevo='. $newAverageCost );
    }

    static public function getProduct($movement){
        return ProductWarehouse::where('warehouse_id', $movement->warehouse_id)
                            ->where('product_id', $movement->product_id)
                            ->first();
    }

    static public function getLastPurchasePrice($movement)
    {
        $last_purchase = Movement::where('warehouse_id',$movement->warehouse_id)
                                        ->where('product_id',$movement->product_id)
                                        ->where('key_movement_id',$movement->key_movement->id)
                                        ->latest()
                                        ->first();
        return  $last_purchase ? $last_purchase->cost : 0;

    }


}
