<?php

namespace App\Filament\Resources\ParteResource\Pages;

use App\Filament\Resources\ParteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartes extends ListRecords
{
    protected static string $resource = ParteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
