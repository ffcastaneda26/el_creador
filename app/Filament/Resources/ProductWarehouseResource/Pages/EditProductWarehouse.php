<?php

namespace App\Filament\Resources\ProductWarehouseResource\Pages;

use App\Filament\Resources\ProductWarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProductWarehouse extends EditRecord
{
    protected static string $resource = ProductWarehouseResource::class;

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
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
