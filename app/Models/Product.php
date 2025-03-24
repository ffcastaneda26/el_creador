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

    public function has_manufacturings()
    {
        return $this->manufacturings()->count();
    }
    public function has_warehouses(){
        return $this->warehouses()->count();
    }
    public function warehouse_requests(): hasMany
    {
        return $this->hasMany(WarehouseRequestDetail::class,'product_id');
    }

    public function has_requests()
    {
        return $this->warehouse_requests()->count();
    }
    public function has_purchases()
    {
        return $this->warehouse_requests()->count();
    }
    public function has_movements()
    {
        return $this->movements()->count();
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

    public function has_receptions()
    {
        return $this->reception_details()->count();
    }


    public function can_delete(){
        return !$this->has_warehouses()
            && !$this->has_manufacturings()
            && !$this->has_requests()
            && !$this->has_movements()
            && !$this->has_purchases()
            && !$this->has_receptions();
    }
}
