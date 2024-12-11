<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogosManufacturing extends Model
{
    protected $table = "logos_manufacturing";

    public $timestamps = false;
    protected $fillable =  [
        'manufacturing_id',
        'anexo_id',
        'ubicacion',
        'material',
        'tamano'
    ];

    public function anexo():BelongsTo
    {
        return $this->belongsTo(Anexo::class);
    }

    public function manufacturing(): BelongsTo
    {
        return $this->belongsTo(Manufacturing::class);
    }
}
