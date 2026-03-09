<?php

namespace App\Observers;

use App\Models\WarehouseRequest;
use App\Support\RoleNotifier;

class WarehouseRequestObserver
{
    public function created(WarehouseRequest $request): void
    {
        RoleNotifier::notify(
            ['Administrador', 'Administrador Contador', 'Dueno CEO', 'Direccion', 'Gerente', 'Almacen', 'Almacén', 'Gerente CAE'],
            'Nueva solicitud de almacen',
            'Se creo la solicitud #' . $request->folio
        );
    }
}

