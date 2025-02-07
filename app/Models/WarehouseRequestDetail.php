<?php

namespace App\Models;

use App\Enums\Enums\StatusWareHouseRequestDetailEnum;
use Illuminate\Database\Eloquent\Model;

class WarehouseRequestDetail extends Model
{
    protected $table = 'warehouse_request_details';

    protected $fillable =  [
        'warehouse_request_id',
        'product_id',
        'quantity',
        'quantity_delivered',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status'    => StatusWareHouseRequestDetailEnum::class,
        ];
    }

    public function warehouse_request()
    {
        return $this->belongsTo(WarehouseRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPending()
    {
        return $this->quantity - $this->quantity_delivered;
    }

    public function updateDelivery($quantity=0){
        $this->quantity_delivered = $this->quantity_delivered + $quantity;
        $this->save();
        $this->updateStatus();
    }

    public function updateStatus()
    {
        if ($this->quantity_delivered == 0) {
            $this->status = StatusWareHouseRequestDetailEnum::pendiente;
        } elseif ($this->quantity == $this->quantity_delivered) {
            $this->status = StatusWareHouseRequestDetailEnum::surtida;
        } else {
            $this->status = StatusWareHouseRequestDetailEnum::parcial;
        }
        $this->save();
    }


    public function hasPending(){
        return $this->quantity > $this->quantity_delivered;
    }
}
