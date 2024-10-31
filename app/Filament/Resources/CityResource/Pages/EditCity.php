<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
       
        return [
            Actions\DeleteAction::make()
            ->visible(auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador() )
            ->disabled(! (auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador())),
        ];
    
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
