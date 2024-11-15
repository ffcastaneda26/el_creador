<?php

namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use App\Filament\Asesor\Resources\CotizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $subtotal = round($data['subtotal'],2);
        $descuento = round($data['descuento'],2);
        $envio = round($data['envio'],2);

        $iva = 0;
        if($data['tax']){
            $iva = round($subtotal * 0.16,2);
        }
        $total = round($subtotal + $iva - $descuento + $envio,2);
        $data['subtotal']   = $subtotal;
        $data['descuento']  = $descuento;
        $data['iva']        = $iva;
        $data['total']      = $total;
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
