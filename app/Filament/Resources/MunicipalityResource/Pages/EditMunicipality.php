<?php

namespace App\Filament\Resources\MunicipalityResource\Pages;

use App\Filament\Resources\MunicipalityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Container\Attributes\Auth;

class EditMunicipality extends EditRecord
{
    protected static string $resource = MunicipalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->visible(Auth::user()->isSuperAdmin())
            ->disabled(!Auth::user()->isSuperAdmin()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
