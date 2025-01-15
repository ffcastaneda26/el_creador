<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManufacturingProduct extends Model
{
    protected $table = "manufacturing_products";
    public $timestamps = false;
    protected $fillable = [
        'manufacturing_id',
        'product_id'
    ];



    public function manufacturing(): BelongsTo
    {
        return $this->belongsTo(Manufacturing::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
