<?php
namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use App\Filament\Asesor\Resources\CotizationResource;
use App\Models\Client;
use App\Models\Cotization;
use App\Models\Order;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $subtotal = 0;
        if (! isset($data['client_id'])) {
            $cotization        = $this->getRecord();
            $data['client_id'] = $cotization->client_id;
        }
        if ($cotization->details) {
            foreach ($cotization->details as $detail) {
                $subtotal += round(floatval($detail['price'] ?? 0) * floatval($detail['quantity'] ?? 0), 2);
            }
        }

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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Se asegura de que el registro es del tipo Cotization para acceder a sus atributos.
        /** @var \App\Models\Cotization $cotization */
        $cotization = $record;

        $wasApproved = $cotization->aprobada;

        // Actualiza la cotización
        $cotization->update($data);

        // Si la cotización fue aprobada en la actualización y no estaba aprobada antes
        if ($data['aprobada'] && ! $wasApproved) {
            $this->createOrderFromCotization($cotization);
            Notification::make()->title('Se actualizó la cotización y se creó una nueva orden de compra.')->success()->send();
        }

        return $cotization;
    }
    protected function createOrderFromCotization(Cotization $cotization)
    {
        // Lógica para mapear los campos de la cotización a la orden
        $orderData = [
            'client_id'       => $cotization->client_id,
            'date'            => now(),
            'approved'        => false,
            'subtotal'        => $cotization->subtotal,
            'tax'             => $cotization->iva,
            'retencion_isr'   => $cotization->retencion_isr,
            'discount'        => $cotization->descuento,
            'total'           => $cotization->total,
            'shipping_cost'   => $cotization->envio,
            'cotization_id'   => $cotization->id, // Asigna el ID de la cotización
            'user_id'         => $cotization->user_id,
            'require_invoice' => $cotization->require_invoice,
        ];

        Order::create($orderData);
    }


}
