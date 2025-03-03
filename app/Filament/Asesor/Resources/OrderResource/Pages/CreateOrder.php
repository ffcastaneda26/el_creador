<?php

namespace App\Filament\Asesor\Resources\OrderResource\Pages;

use App\Filament\Asesor\Resources\OrderResource;
use App\Models\Zipcode;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $zipcode = Zipcode::where('zipcode', $data['zipcode'])->first();
        $data['country_id']     = $zipcode->country_id;
        $data['state_id']       = $zipcode->state_id;
        $data['municipality_id'] = $zipcode->municipality_id;
        $data['city_id']        = $zipcode->city_id;
        $data['user_id']        = Auth::user()->id;
        return $data;
    }
    protected function beforeFill(): void
    {
        // Runs before the form fields are populated from the database.
        $record = $this->getRecord();
        dd($record);
    }
}
