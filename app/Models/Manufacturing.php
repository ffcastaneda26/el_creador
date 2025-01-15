<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Manufacturing extends Model
{
    protected $table = "manufacturings";
    protected $fillable = [
        'folio',
        'order_id',
        'asesor_id',
        'botarga',
        'fecha_inicio',
        'fecha_fin',
        'observaciones_cabeza',
        'observaciones_cuerpo',
        'observaciones_estructura',
        'observaciones_body_interno',
        'observaciones_outfit1',
        'observaciones_outfit2',
        'observaciones_zapatos',
        'observaciones_accesorios',
        'observaciones_logotipos',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime:Y-m-d',
            'fecha_fin' => 'datetime:Y-m-d',
        ];
    }


    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class,'asesor_id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function logotipos():HasMany
    {
        return $this->hasMany(LogosManufacturing::class);
    }


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function parts(): HasMany
    {
        return $this->hasMany(ManufacturingPart::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): hasMany
    {
        return $this->hasMany(ManufacturingProduct::class);
    }
}
