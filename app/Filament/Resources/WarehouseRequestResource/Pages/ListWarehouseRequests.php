<?php

namespace App\Filament\Resources\WarehouseRequestResource\Pages;

use App\Filament\Resources\WarehouseRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouseRequests extends ListRecords
{
    protected static string $resource = WarehouseRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
