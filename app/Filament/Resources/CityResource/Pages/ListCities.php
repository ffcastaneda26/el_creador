<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->visible(auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador())
            ->disabled(!(auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador())),
        ];
    }
}
