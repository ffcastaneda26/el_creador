<?php

namespace App\Observers;

use App\Models\Order;
use App\Support\RoleNotifier;

class OrderObserver
{
    public function created(Order $order): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Direccion', 'Dirección', 'Gerente', 'Almacen', 'Almacén'],
            'Nueva orden de compra',
            'Se creó la orden de compra #' . $order->id
        );
    }
}
