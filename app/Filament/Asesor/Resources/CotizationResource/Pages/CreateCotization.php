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
        // $subtotal = round($data['subtotal'],2);
        // $descuento = round($data['descuento'],2);
        // $envio = round($data['envio'],2);
        // $retencion_isr = 0;
        // $iva = 0;
        // if($data['require_invoice']){
        //     $percentage_iva = round(env('PERCENTAGE_IVA', 16) / 100, 2);
        //     $percentage_retencion = env('PERCENTAGE_RETENCION_ISR', 1.25);
        //     $base_retencion = round($subtotal - $descuento + $envio, 2);
        //     $iva = round($base_retencion * $percentage_iva, 2);
        //     $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
        // }

        // $total = round($subtotal + $iva - $descuento + $envio - $retencion_isr,2);
        // $data['subtotal']   = $subtotal;
        // $data['descuento']  = $descuento;
        // $data['iva']        = $iva;
        // $data['retencion_isr']      = $retencion_isr;
        // $data['total']      = $total;
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
