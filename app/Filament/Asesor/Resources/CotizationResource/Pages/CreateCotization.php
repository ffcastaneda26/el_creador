<?php
namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use App\Filament\Asesor\Resources\CotizationResource;
use App\Models\Client;
use App\Models\Cotization;
use App\Models\Order;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateCotization extends CreateRecord
{
    protected static string $resource = CotizationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['client_id'])) {
            throw ValidationException::withMessages([
                'client_id' => 'Debes seleccionar un cliente.',
            ]);
        }

        $subtotal = round((float) ($data['subtotal'] ?? 0), 2);
        $descuento = round((float) ($data['descuento'] ?? 0), 2);
        $envio = round((float) ($data['envio'] ?? 0), 2);

        $iva = 0.00;
        $retencionIsr = 0.00;

        $client = Client::find($data['client_id']);
        $isNoFiscal = $client && $client->type === 'Sin Efectos Fiscales';

        if ($isNoFiscal) {
            $data['require_invoice'] = false;
        } else {
            $data['require_invoice'] = (bool) ($data['require_invoice'] ?? true);
        }

        $taxSelections = collect($data['tax'] ?? []);

        if ($data['require_invoice'] && $taxSelections->isEmpty()) {
            $taxSelections = collect(CotizationResource::defaultTaxKeys());
        }

        $data['tax'] = $data['require_invoice'] ? $taxSelections->values()->all() : [];

        $base = max(round($subtotal - $descuento + $envio, 2), 0);

        if ($data['require_invoice']) {
            $taxConfig = collect(CotizationResource::getTaxesConfig())->keyBy('key');

            foreach ($taxSelections as $taxKey) {
                $tax = $taxConfig->get($taxKey);

                if (! $tax) {
                    continue;
                }

                $amount = round($base * ($tax['percent'] / 100), 2);

                if ($tax['type'] === 'add') {
                    $iva += $amount;
                }

                if ($tax['type'] === 'retention') {
                    $retencionIsr += $amount;
                }
            }
        }

        $total = round($base + $iva - $retencionIsr, 2);

        $data['subtotal'] = $subtotal;
        $data['descuento'] = $descuento;
        $data['iva'] = round($iva, 2);
        $data['retencion_isr'] = round($retencionIsr, 2);
        $data['total'] = $total;
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function handleRecordCreation(array $data): Cotization
    {
        $cotization = Cotization::create($data);

        if ($cotization->aprobada) {
            $this->createOrderFromCotization($cotization);
            Notification::make()->title('Se creo la cotizacion y la orden de compra')->success()->send();

            return $cotization;
        }

        Notification::make()->title('Se creo cotizacion')->success()->send();

        return $cotization;
    }

    protected function createOrderFromCotization(Cotization $cotization): void
    {
        $orderData = [
            'client_id' => $cotization->client_id,
            'date' => now(),
            'approved' => false,
            'subtotal' => $cotization->subtotal,
            'tax' => $cotization->iva,
            'retencion_isr' => $cotization->retencion_isr,
            'discount' => $cotization->descuento,
            'total' => $cotization->total,
            'shipping_cost' => $cotization->envio,
            'cotization_id' => $cotization->id,
            'user_id' => $cotization->user_id,
            'require_invoice' => $cotization->require_invoice,
        ];

        Order::create($orderData);
    }
}
