<?php

namespace App\Helpers;
use App\Models\Movement;
use App\Models\KeyMovement;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Log;

class InventoryManagement
{
    static public function updateStockX($movement,$type='normal')
    {
        try {
            $product =ProductWarehouse::where('warehouse_id', $movement->warehouse_id)
                                    ->where('product_id', $movement->product_id)
                                    ->first();

            if($type == 'normal'){
                $product->stock           = $movement->key_movement->isTypeI() ? $product->stock + $movement->quantity : $product->stock - $movement->quantity;
                $product->stock_available = $movement->key_movement->isTypeI() ? $product->stock_available + $movement->quantity : $product->stock_available - $movement->quantity;
                $product->save();
            }
            if($type == 'delete'){
                $product->stock           = $movement->key_movement->isTypeI() ? $product->stock           - $movement->quantity : $product->stock           + $movement->quantity;
                $product->stock_available = $movement->key_movement->isTypeI() ? $product->stock_available - $movement->quantity : $product->stock_available + $movement->quantity;
                $product->save();
            }

        } catch (\Throwable $th) {
            Log::info("Movimientos de almacén  " . $th->getMessage());
            return false;
        }
       
  
    }
    static public function updateStock($movement,$type='normal')
    {
        try {
            $product = self::getProduct($movement);
            if ($movement->key_movement->require_cost) {
                $product->average_cost = self::calculateAverageCost($product,$movement,$type);
            }
 
            if($type == 'normal'){
                $existenciaAntes = $product->stock;
                $newStock = self::calculateNewStock($movement->key_movement,$product->stock,$movement->quantity,$type);
                $product->stock           = $newStock;
                $product->stock_available = $product->stock - $product->stock_compromised;
                if ($movement->key_movement->is_purchase ) {
                    $product->last_purchase_price = self::calculateLastPurchasePrice($product,$movement,$type);
                }
                $product->save();
            } 

            if($type =='delete'){
                if ($movement->key_movement->is_purchase) { 
                    $last_purchase = Movement::where('warehouse_id',$movement->wareouse_id)
                                        ->where('product_id',$movement->product_id)
                                        ->where('key_movement_id',$movement->key_movement->id)
                                        ->latest()
                                        ->first();
                   
                    if($last_purchase){
                        $product->last_purchase_price =  $last_purchase->cost;
                    }
                }
                $product->stock           = $movement->key_movement->isTypeI() ? $product->stock           - $movement->quantity : $product->stock           + $movement->quantity;
                $product->stock_available = $movement->key_movement->isTypeI() ? $product->stock_available - $movement->quantity : $product->stock_available + $movement->quantity;
                $product->save();
            }

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
        $isTypeI = $key_movement->isTypeI();
        if($type == 'normal'){
           return  $isTypeI ? $currentStock + $quantity : $currentStock - $quantity;
        }
        if($type =='delete'){
            return $isTypeI ? $currentStock - $quantity : $currentStock + $quantity;
        }
   
    }
    static public function calculateAverageCost($product,$movement,$type='normal')
    {
        $currentTotalCost = self::getTotalCost($product->stock,$product->average_cost);
        $amountMovement = self::getAmountMovement($movement->quantity,$movement->cost);
        
        if($type == 'normal'){
            $newStock = self::getNewStock($product->stock,$movement->quantity);
        }
        if($type == 'delete'){
            $newStock = self::getNewStock($product->stock,$movement->quantity*-1);
        }
        $newAverageCost = ( $currentTotalCost + $amountMovement ) / $newStock;
        // self::show_values($product->stock, $product->average_cost, $movement->quantity, $movement->cost,$type,$newStock,$newAverageCost);
        return ( $currentTotalCost + $amountMovement ) / $newStock;
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
}
