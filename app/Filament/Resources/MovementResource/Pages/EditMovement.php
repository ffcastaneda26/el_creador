<?php

namespace App\Filament\Resources\MovementResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MovementResource;
use App\Helpers\InventoryManagement;

class EditMovement extends EditRecord
{
    protected static string $resource = MovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();
        if($record->key_movement->require_cost){
            $quantity_before = $record->quantity;
            $cost_before= $record->cost;
            if($quantity_before != $data['quantity'] || $cost_before != $data['cost']){
                InventoryManagement::updateStock($record,'delete');
            }
        }





        $data['amount'] = round( $data['quantity'] * $data['cost'],6);
        $data['user_id'] = Auth::user()->id;
        return $data;
    }

    // protected function afterSave(): void
    // {
    //     $warehouseId=$this->record->warehouse_id;
    //     $productId=$this->record->product_id;
    //     $keyMovementId= $this->record->key_movement_id;
    //     $quantity = $this->record->quantity;
    //     $cost= $this->record->cost;
    //     InventoryManagement::updateStock($warehouseId,$productId,$keyMovementId,$quantity,$cost);
    //     InventoryManagement::updateStockAndCost($this->record);
    // }

}
