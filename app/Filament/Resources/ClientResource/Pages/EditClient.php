<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Zipcode;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $zipcode = Zipcode::where('zipcode', $data['zipcode'])->first();
        $data['country_id'] = $zipcode->country_id;
        $data['state_id'] = $zipcode->state_id;
        $data['municipality_id'] = $zipcode->municipality_id;
        $data['city_id'] = $zipcode->city_id;
        return $data;
    }

}
