<?php

namespace App\Filament\Resources\KeyMovementResource\Pages;

use App\Filament\Resources\KeyMovementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateKeyMovement extends CreateRecord
{
    protected static string $resource = KeyMovementResource::class;
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
