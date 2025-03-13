<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Enums\Enums\StatusPurchaseDetailEnum;


class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';
    protected $fillable =  [
        'purchase_id',
        'product_id',
        'quantity',
        'quantity_received',
        'cost',
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
        return $this->quantity - $this->quantity_received;
    }

    public function updateDelivery($quantity=0){
        $this->quantity_received = $this->quantity_received + $quantity;
        $this->save();
        $this->updateStatus();
    }

    public function updateStatus()
    {
        if ($this->quantity_received == 0) {
            $this->status = StatusPurchaseDetailEnum::pendiente;
        } elseif ($this->quantity == $this->quantity_received) {
            $this->status = StatusPurchaseDetailEnum::surtida;
        } else {
            $this->status = StatusPurchaseDetailEnum::parcial;
        }
        $this->save();
    }


    public function hasPending(){
        return $this->quantity > $this->quantity_received;
    }

    public function has_pendings_to_suply(): bool
    {
        return $this->pendings_to_supply()->count() > 0;
    }
}
