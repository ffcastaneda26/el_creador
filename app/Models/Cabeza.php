<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cabeza extends Model
{
    protected $table = 'cabezas';
    public $timestamps = false;
    protected $fillable =  [
        'modelo',
        'tamano',
        'fibra',
        'polyfoam',
        'full_print',
        'ventilacion',
        'pila',
        'cargador',
    ];

    public function orden_fabricacion(): HasOne
    {
        return $this->hasOne(OrdenFabricacion::class);
    }

}
