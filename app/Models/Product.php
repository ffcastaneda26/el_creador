<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'price',
        'last_purchase_price',
        'stock',
        'stock_available',
        'stock_min',
        'stock_max',
        'stock_reorder',
        'average_cost',
        'image',
        'user_id'
    ];




    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
