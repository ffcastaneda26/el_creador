<?php

namespace App\Models;

use App\Observers\MovementObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[Observedby([MovementObserver::class])]
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
        'amount',
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
