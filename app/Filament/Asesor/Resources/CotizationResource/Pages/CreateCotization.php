<?php

namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Asesor\Resources\CotizationResource;

class CreateCotization extends CreateRecord
{
    protected static string $resource = CotizationResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $subtotal = round($data['subtotal'],2);
        $descuento = round($data['descuento'],2);
        $iva = round($subtotal * 0.16,2);
        $total = round($subtotal + $iva + $descuento,2);

        $data['subtotal']   = $subtotal;
        $data['descuento']  = $descuento;
        $data['iva']        = $iva;
        $data['total']      = $total;

        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
