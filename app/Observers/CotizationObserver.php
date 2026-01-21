<?php

namespace App\Observers;

use App\Models\Cotization;
use App\Support\RoleNotifier;

class CotizationObserver
{
    public function created(Cotization $cotization): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Direccion', 'Dirección', 'Gerente', 'Asesor'],
            'Nueva cotización',
            'Se creó la cotización #' . $cotization->id
        );
    }
}
