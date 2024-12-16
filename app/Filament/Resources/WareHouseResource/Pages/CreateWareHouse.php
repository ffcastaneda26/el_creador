<?php

namespace App\Filament\Resources\WareHouseResource\Pages;

use App\Filament\Resources\WareHouseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateWareHouse extends CreateRecord
{
    protected static string $resource = WareHouseResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
