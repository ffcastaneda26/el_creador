<?php

namespace App\Filament\Resources\ManufacturingResource\Pages;

use App\Filament\Resources\ManufacturingResource;
use App\Models\Manufacturing;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateManufacturing extends CreateRecord
{
    protected static string $resource = ManufacturingResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
    $manufacturing = Manufacturing::find( $data['folio']);
        if($manufacturing){
              $data['folio'] = Manufacturing::max('id')+1;
        }
        $data['user_id']    = Auth::user()->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
