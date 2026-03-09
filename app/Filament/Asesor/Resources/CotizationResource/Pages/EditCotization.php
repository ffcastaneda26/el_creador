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
use Illuminate\Validation\ValidationException;

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
        $subtotal = 0.00;
        $cotization = $this->getRecord();

        if (! isset($data['client_id']) && $cotization) {
            $data['client_id'] = $cotization->client_id;
        }

        if (empty($data['client_id'])) {
            throw ValidationException::withMessages([
                'client_id' => 'Debes seleccionar un cliente.',
            ]);
        }

        $details = $cotization?->details ?? [];
        foreach ($details as $detail) {
            $subtotal += round(floatval($detail['price'] ?? 0) * floatval($detail['quantity'] ?? 0), 2);
        }

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

        $data['subtotal'] = round($subtotal, 2);
        $data['descuento'] = $descuento;
        $data['iva'] = round($iva, 2);
        $data['retencion_isr'] = round($retencionIsr, 2);
        $data['total'] = $total;
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var \App\Models\Cotization $cotization */
        $cotization = $record;
        $wasApproved = $cotization->aprobada;

        $cotization->update($data);

        if ($data['aprobada'] && ! $wasApproved) {
            $this->createOrderFromCotization($cotization);
            Notification::make()->title('Se actualizo la cotizacion y se creo una nueva orden de compra.')->success()->send();
        }

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
