<?php

namespace App\Filament\Resources\ParteResource\Pages;

use App\Filament\Resources\ParteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParte extends EditRecord
{
    protected static string $resource = ParteResource::class;

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
}
