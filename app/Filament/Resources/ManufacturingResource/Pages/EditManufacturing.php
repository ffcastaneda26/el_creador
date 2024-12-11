<?php

namespace App\Filament\Resources\ManufacturingResource\Pages;

use App\Filament\Resources\ManufacturingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditManufacturing extends EditRecord
{
    protected static string $resource = ManufacturingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id']    = Auth::user()->id;
        return $data;
    }
}
