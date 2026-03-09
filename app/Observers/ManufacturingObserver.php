<?php

namespace App\Observers;

use App\Models\Manufacturing;
use App\Support\RoleNotifier;

class ManufacturingObserver
{
    public function created(Manufacturing $manufacturing): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Administrador Contador', 'Dueno CEO', 'Direccion', 'Gerente', 'Director Produccion', 'Gerente Produccion', 'Produccion', 'Producción'],
            'Nueva orden de fabricacion',
            'Se creo la orden de fabricacion #' . $manufacturing->folio
        );
    }
}

