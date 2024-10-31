<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function beforeCreate(): void
    {
        if (! (auth()->user()->isSuperAdmin() || auth()->user()->isAdministrador() )) {
            Notification::make()
                ->warning()
                ->title(__('Restricted Action'))
                ->body(__('You are not authorized to create new records, please consult your administrator'))
                ->persistent()
                ->send();
            $this->halt();
        }
    }
}
