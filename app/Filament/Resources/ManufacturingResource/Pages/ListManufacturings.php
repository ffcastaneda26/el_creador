<?php

namespace App\Filament\Resources\ManufacturingResource\Pages;

use App\Filament\Resources\ManufacturingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManufacturings extends ListRecords
{
    protected static string $resource = ManufacturingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
