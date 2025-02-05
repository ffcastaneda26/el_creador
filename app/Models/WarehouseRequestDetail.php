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

    public function getPendingAttribute()
    {
        return $this->quantity - $this->quantity_delivered;
    }

}
