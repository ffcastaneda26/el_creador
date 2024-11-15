<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Cotization extends Model
{

    protected $table = 'cotizations';

    protected $fillable =  [
        'client_id',
        'fecha',
        'vigencia',
        'aprobada',
        'fecha_aprobada',
        'description',
        'subtotal',
        'iva',
        'descuento',
        'envio',
        'total',
        'fecha_entrega',
        'user_id'
    ];
    protected function casts(): array
    {
        return [
            'fecha'         => 'datetime',
            'vigencia'      => 'datetime',
            'fecha_aprobada'=> 'datetime',
            'fecha_entrega' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
