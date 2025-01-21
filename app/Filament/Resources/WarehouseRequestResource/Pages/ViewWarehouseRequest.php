<?php

namespace App\Filament\Resources\WarehouseRequestResource\Pages;

use App\Filament\Resources\WarehouseRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWarehouseRequest extends ViewRecord
{
    protected static string $resource = WarehouseRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
