<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Enums\Enums\StatusPurchaseEnum;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;

use App\Filament\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->button()->color('info'),
            Actions\DeleteAction::make()
            ->visible(fn ($record) => $record->status == StatusPurchaseEnum::abierto || !$record->has_details_received())

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
