<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Order;
use App\Support\RoleNotifier;

class OrderObserver
{
    public function created(Order $order): void
    {
        $this->syncCalendarEvent($order);

        RoleNotifier::notify(
            ['Administrador', 'Administrador Contador', 'Dueno CEO', 'Direccion', 'Gerente', 'Director Ventas', 'Gerente Ventas', 'Almacen', 'Gerente CAE'],
            'Nueva orden de compra',
            'Se creo la orden de compra #' . $order->id
        );
    }

    public function updated(Order $order): void
    {
        $this->syncCalendarEvent($order);
    }

    public function deleted(Order $order): void
    {
        Event::query()->where('order_id', $order->id)->delete();
    }

    private function syncCalendarEvent(Order $order): void
    {
        $order->loadMissing('client');

        $start = ($order->date ?? now())->copy()->startOfDay();
        $end = $order->delivery_date ? $order->delivery_date->copy()->endOfDay() : $start->copy()->endOfDay();

        if ($end->lt($start)) {
            $end = $start->copy()->endOfDay();
        }

        $clientName = trim((string) ($order->client?->full_name ?? 'Cliente sin nombre'));
        $motleyName = trim((string) ($order->motley_name ?? ''));

        $title = trim(implode(' - ', array_filter([$motleyName, $clientName])));
        if ($title === '') {
            $title = 'Orden de compra #' . ($order->folio ?: $order->id);
        }

        Event::query()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'title' => $title,
                'description' => 'Evento generado desde orden de compra #' . ($order->folio ?: $order->id),
                'color' => '#16a34a',
                'starts_at' => $start,
                'ends_at' => $end,
            ]
        );
    }
}

