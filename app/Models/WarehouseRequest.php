<?php

namespace App\Models;

use App\Enums\Enums\StatusWareHouseRequestDetailEnum;
use App\Enums\Enums\StatusWarehouseRequestEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function pendings_to_supply(): HasMany
    {
        return $this->hasMany(WarehouseRequestDetail::class)->where('status', '!=',StatusWareHouseRequestDetailEnum::surtida);
    }

    public function has_pendings_to_suply(): bool
    {
        return $this->pendings_to_supply()->count() > 0;
    }

    public function partial_supply_items(): HasMany
    {
        return $this->hasMany(WarehouseRequestDetail::class)->where('status', '!=',StatusWareHouseRequestDetailEnum::parcial);
    }

    public function has_partial_suply(): bool
    {
        return $this->partial_supply_items()->count() > 0;
    }

    public function can_open_again()
    {
        return $this->pendings_to_supply()->count() == $this->details()->count();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function can_be_suply(): bool
    {
        return (StatusWarehouseRequestEnum::autorizado && $this->has_pendings_to_suply()) || !StatusWarehouseRequestEnum::cancelado ;

    }
    /**
     * Actualiza Estado
     */

     public function updateStatus()
     {

        if(!$this->has_pendings_to_suply()){
            $this->status = StatusWarehouseRequestEnum::surtido;
        }
        if(!$this->has_partial_suply()){
            $this->status = StatusWarehouseRequestEnum::parcial;
        }


        $this->save();

     }


}
