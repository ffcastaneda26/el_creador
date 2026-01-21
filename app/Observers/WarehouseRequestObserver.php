<?php

namespace App\Observers;

use App\Models\WarehouseRequest;
use App\Support\RoleNotifier;

class WarehouseRequestObserver
{
    public function created(WarehouseRequest $request): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Direccion', 'Dirección', 'Gerente', 'Almacen', 'Almacén'],
            'Nueva solicitud de almacén',
            'Se creó la solicitud #' . $request->folio
        );
    }
}
