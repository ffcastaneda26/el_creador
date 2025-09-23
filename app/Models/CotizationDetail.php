<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
