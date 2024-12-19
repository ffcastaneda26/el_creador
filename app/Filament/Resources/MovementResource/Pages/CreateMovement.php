<?php

namespace App\Filament\Resources\MovementResource\Pages;

use App\Filament\Resources\MovementResource;
use App\Models\KeyMovement;
use App\Models\ProductWarehouse;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use  App\Helpers\InventoryManagement;
use App\Models\Warehouse;

class CreateMovement extends CreateRecord
{
    protected static string $resource = MovementResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['warehouse_id'] = Warehouse::first()->id;
        $key_movement = KeyMovement::findOrFail($data['key_movement_id']);
        if (!$key_movement->require_cost) {
            $warehouse_record = ProductWarehouse::where('warehouse_id', $data['warehouse_id'])
                ->where('product_id', $data['product_id'])
                ->first();
            $data['cost'] = $warehouse_record->average_cost;
        }

        $data['status'] = 'Aplicado';
        $data['user_id'] = Auth::user()->id;
        $data['amount'] = round( $data['quantity'] * $data['cost'],6);
        return $data;
    }



}
