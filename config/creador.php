<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Impuestos
    |--------------------------------------------------------------------------
    |
    | Porcentajes usados al calcular el total de un pedido cuando el cliente
    | requiere factura. Se leen desde aqui y no con env() directo, porque en
    | produccion la configuracion se cachea y env() deja de ver el archivo .env.
    |
    */

    'percentage_iva' => (float) env('PERCENTAGE_IVA', 16),

    'percentage_retencion_isr' => (float) env('PERCENTAGE_RETENCION_ISR', 1.25),

    /*
    |--------------------------------------------------------------------------
    | Opciones de movimientos de almacen
    |--------------------------------------------------------------------------
    */

    'use_voucher_image' => (bool) env('USE_VOUCHER_IMAGE', false),

    'use_movement_notes' => (bool) env('USE_MOVEMENT_NOTES', false),

];
