<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    public $timestamps = false;
    protected $fillable =  [
        'name',
        'code',
        'unit_id',
        'description',
        'image',
        'user_id'
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(ProductWarehouse::class);
    }

    public function manufacturings(): hasMany
    {
        return $this->hasMany(ManufacturingProduct::class);
    }

    public function warehouse_requests(): hasMany
    {
        return $this->hasMany(WarehouseRequestDetail::class,'product_id');
    }

    public static function hasRecords()
    {
        return self::count();
    }

    public function purchase_details(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class,'product_id');
    }

    public function reception_details(): HasMany
    {
        return $this->hasMany(ReceiptDetail::class,'product_id');
    }

    // public function warehouses(): BelongsToMany
    // {
    //     return $this->belongsToMany(Product::class)
    //             ->withPivot('price',
    //                                 'last_purchase_price',
    //                                 'stock',
    //                                 'stock_available',
    //                                 'stock_compromised',
    //                                 'stock_min',
    //                                 'stock_max',
    //                                 'stock_reorder',
    //                                 'average_cost',
    //                                 'active',
    //                                 'user_id');
    // }
}
