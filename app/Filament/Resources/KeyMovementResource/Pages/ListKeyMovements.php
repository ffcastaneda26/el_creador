<?php

namespace App\Filament\Resources\KeyMovementResource\Pages;

use App\Filament\Resources\KeyMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeyMovements extends ListRecords
{
    protected static string $resource = KeyMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
