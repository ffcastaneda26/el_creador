<?php
namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use App\Filament\Asesor\Resources\CotizationResource;
use App\Models\Client;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCotization extends CreateRecord
{
    protected static string $resource = CotizationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $subtotal                = round($data['subtotal'], 2);
        $descuento               = round($data['descuento'], 2);
        $envio                   = round($data['envio'], 2);
        $retencion_isr           = 0;
        $iva                     = 0;
        $client                  = Client::find($data['client_id']);
        $data['require_invoice'] = $client && $client->type !== 'Sin Efectos Fiscales';

        if ($data['require_invoice']) {
            $percentage_iva       = round(env('PERCENTAGE_IVA', 16) / 100, 2);
            $percentage_retencion = env('PERCENTAGE_RETENCION_ISR', 1.25);
            $base_retencion       = round($subtotal - $descuento + $envio, 2);
            $iva                  = round($base_retencion * $percentage_iva, 2);
            $retencion_isr        = round($base_retencion * ($percentage_retencion / 100), 2);
        }

        $total                 = round($subtotal + $iva - $descuento + $envio - $retencion_isr, 2);
        $data['subtotal']      = $subtotal;
        $data['descuento']     = $descuento;
        $data['iva']           = $iva;
        $data['retencion_isr'] = $retencion_isr;
        $data['total']         = $total;
        $data['user_id']       = Auth::user()->id;
        return $data;
    }

}
