<?php

namespace App\Filament\Resources\ProductWarehouseResource\Pages;

use Filament\Actions;
use App\Models\Movement;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductWarehouseResource;

class EditProductWarehouse extends EditRecord
{
    protected static string $resource = ProductWarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->hidden(function(ProductWarehouse $record){
                return Movement::where('warehouse_id',$record->warehouse_id)
                ->where('product_id',$record->product_id)
                ->count();
            }),
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
