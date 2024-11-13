<?php

namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use App\Filament\Asesor\Resources\CotizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCotization extends EditRecord
{
    protected static string $resource = CotizationResource::class;

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
