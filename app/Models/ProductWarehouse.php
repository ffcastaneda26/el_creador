<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductWarehouse extends Model
{
    protected $table = "product_warehouse";
    protected $fillable =[
        'warehouse_id',
        'product_id',
        'price',
        'last_purchase_price',
        'stock',
        'stock_available',
        'stock_compromised',
        'stock_min',
        'stock_max',
        'stock_reorder',
        'average_cost',
        'active',
        'user_id'
    ];

    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /** Funciones auxiliares */

    protected function totalCost(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->stock * $this->average_cost,
        );
    }
}
