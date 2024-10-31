<?php

namespace App\Filament\Resources\MunicipalityResource\Pages;

use App\Filament\Resources\MunicipalityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListMunicipalities extends ListRecords
{
    protected static string $resource = MunicipalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador())
                ->disabled(!(auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador())),
        ];
    }
}
