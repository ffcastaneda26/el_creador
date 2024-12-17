<?php

namespace App\Filament\Resources\ProductWarehouseResource\Pages;

use App\Filament\Resources\ProductWarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductWarehouses extends ListRecords
{
    protected static string $resource = ProductWarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
