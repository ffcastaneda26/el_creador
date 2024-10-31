<?php

namespace App\Filament\Resources\TypeZipCodeResource\Pages;

use App\Filament\Resources\TypeZipCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTypeZipCode extends EditRecord
{
    protected static string $resource = TypeZipCodeResource::class;

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
