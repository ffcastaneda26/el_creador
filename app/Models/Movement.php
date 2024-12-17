<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movement extends Model
{
    protected $table = 'movements';
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'key_movement_id',
        'date',
        'quantity',
        'cost',
        'reference',
        'notes',
        'status',
        'user_id',
    ];


    protected function casts(): array
    {
        return [
            'date' => 'datetime:Y-m-d',
        ];
    }
    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->quantity * $this->cost,
        );
    }
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function key_movement(): BelongsTo
    {
        return $this->belongsTo(KeyMovement::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
