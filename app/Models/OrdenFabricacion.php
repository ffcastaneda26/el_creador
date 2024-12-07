<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrdenFabricacion extends Model
{
    protected $table = 'ordenes_fabricacion';
    protected $fillable =  [
        'order_id',
        'folio',
        'name',
        'asesor_id',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'  => "Y-m-d",
            'fecha_fin'     => "Y-m-d",
        ];
    }

    public function cabeza():HasOne
    {
        return $this->hasOne(Cabeza::class);
    }
}
