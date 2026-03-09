<?php

namespace App\Observers;

use App\Models\Cotization;
use App\Support\RoleNotifier;

class CotizationObserver
{
    public function created(Cotization $cotization): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Administrador Contador', 'Dueno CEO', 'Direccion', 'Gerente', 'Director Ventas', 'Gerente Ventas', 'Asesor'],
            'Nueva cotizacion',
            'Se creo la cotizacion #' . $cotization->id
        );
    }
}
