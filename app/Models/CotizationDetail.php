<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage; // Añadir la clase Storage

class CotizationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cotization_id',
        'name',
        'quantity',
        'price',
        'description',
        'image',
    ];

    public function cotization(): BelongsTo
    {
        return $this->belongsTo(Cotization::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($detail) {
            // Eliminar la imagen del disco si existe
            if ($detail->image) {
                Storage::disk('public')->delete($detail->image);
            }
        });
    }
}
