<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    /** @use HasFactory<\Database\Factories\WarehouseFactory> */
    use HasFactory;
    protected $table = 'warehouses';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'short',
        'email',
        'phone',
        'rfc',
        'active',
        'user_id',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(ProductWarehouse::class);
    }

    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany(Product::class)
    //                 ->withPivot('price',
    //                         'last_purchase_price',
    //                         'stock',
    //                         'stock_available',
    //                         'stock_compromised',
    //                         'stock_min',
    //                         'stock_max',
    //                         'stock_reorder',
    //                         'average_cost',
    //                         'active',
    //                         'user_id');
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
