<?php

namespace App\Models;

use App\Enums\Enums\StatusWarehouseRequestEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseRequest extends Model
{
    protected $table = 'warehouse_requests';

    protected $fillable =  [
        'warehouse_id',
        'folio',
        'date',
        'reference',
        'notes',
        'user_id',
        'user_auhtorizer_id',
        'status'
    ];
    protected function casts(): array
    {
        return [
            'date'      => 'datetime',
            'status'    => StatusWarehouseRequestEnum::class,
        ];
    }

    public function authorizer_user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_auhtorizer_id');
    }
    public function details()
    {
        return $this->hasMany(WarehouseRequestDetail::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }


}
