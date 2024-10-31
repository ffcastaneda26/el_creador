<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;
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
