<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use Filament\Actions;
use App\Enums\Enums\StatusPurchaseEnum;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PurchaseResource;

class ViewPurchase extends ViewRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->visible(fn ($record) => $record->status == StatusPurchaseEnum::abierto),
        ];
    }
}
