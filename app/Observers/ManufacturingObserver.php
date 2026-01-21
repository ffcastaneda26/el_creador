<?php

namespace App\Observers;

use App\Models\Manufacturing;
use App\Support\RoleNotifier;

class ManufacturingObserver
{
    public function created(Manufacturing $manufacturing): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Direccion', 'Dirección', 'Gerente', 'Produccion', 'Producción'],
            'Nueva orden de fabricación',
            'Se creó la orden de fabricación #' . $manufacturing->folio
        );
    }
}
