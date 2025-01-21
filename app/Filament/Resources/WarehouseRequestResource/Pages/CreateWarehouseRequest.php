<?php

namespace App\Filament\Resources\WarehouseRequestResource\Pages;

use App\Filament\Resources\WarehouseRequestResource;
use App\Models\Warehouse;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateWarehouseRequest extends CreateRecord
{
    protected static string $resource = WarehouseRequestResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['warehouse_id'] = Warehouse::first()->id;
        $data['user_id'] = Auth::user()->id;
        $data['status'] = 'abierto';
        if(strlen($data['reference'])){
            $data['reference'] =strtoupper($data['reference']);
        }

        return $data;
    }
}
