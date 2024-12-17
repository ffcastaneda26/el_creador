<?php

namespace App\Filament\Resources\ProductWarehouseResource\Pages;

use App\Filament\Resources\ProductWarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProductWarehouse extends CreateRecord
{
    protected static string $resource = ProductWarehouseResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
