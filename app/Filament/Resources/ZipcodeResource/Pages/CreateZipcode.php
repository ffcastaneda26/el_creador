<?php

namespace App\Filament\Resources\ZipcodeResource\Pages;

use App\Filament\Resources\ZipcodeResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZipcode extends CreateRecord
{
    protected static string $resource = ZipcodeResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $country = Country::findOrFail($data['country_id']);
        $state = State::findOrFail($data['state_id']);
        $municipality = Municipality::findOrFail($data['municipality_id']);
        $city = City::findOrFail($data['city_id']);

        $data['country']        = $country->country;
        $data['state']          = $state->name;
        $data['municipality']   = $municipality->name;
        $data['city']           = $city->name;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
