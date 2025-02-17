<?php

namespace App\Filament\Resources\WarehouseRequestResource\Pages;

use App\Filament\Resources\WarehouseRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditWarehouseRequest extends EditRecord
{
    protected static string $resource = WarehouseRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        if(strlen($data['reference'])){
            $data['reference'] =strtoupper($data['reference']);
        }

        // Si se autoriza actualiza
        // if($data['status'] == 'autorizado'){
        //     $data['user_auhtorizer_id'] =Auth::user()->id;
        // }
        return $data;
    }
}
