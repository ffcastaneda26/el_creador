<?php

namespace App\Models;

use App\Enums\Enums\StatusPurchaseDetailEnum;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable =  [
        'purchase_id',
        'product_id',
        'quantity',
        'quantity_delivered',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status'    => StatusPurchaseDetailEnum::class,
        ];
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
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
            $this->status = StatusPurchaseDetailEnum::pendiente;
        } elseif ($this->quantity == $this->quantity_delivered) {
            $this->status = StatusPurchaseDetailEnum::surtida;
        } else {
            $this->status = StatusPurchaseDetailEnum::parcial;
        }
        $this->save();
    }


    public function hasPending(){
        return $this->quantity > $this->quantity_delivered;
    }
}
