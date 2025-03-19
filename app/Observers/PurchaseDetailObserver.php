<?php

namespace App\Observers;

use App\Enums\Enums\StatusPurchaseDetailEnum;
use App\Models\PurchaseDetail;

class PurchaseDetailObserver
{
    /**
     * Handle the PurchaseDetail "created" event.
     */
    public function created(PurchaseDetail $purchaseDetail): void
    {
        $this->updatePurchaseAmount($purchaseDetail);
    }

    /**
     * Handle the PurchaseDetail "updated" event.
     */
    public function updated(PurchaseDetail $purchaseDetail): void
    {
        $this->updatePurchaseAmount($purchaseDetail);
        // $this->updatePurchaseStatus($purchaseDetail);
        // return;
    }


    /**
     * Handle the PurchaseDetail "deleted" event.
     */
    public function deleted(PurchaseDetail $purchaseDetail): void
    {
        $this->updatePurchaseAmount($purchaseDetail);
    }

    private function updatePurchaseAmount(PurchaseDetail $purchaseDetail): void
    {
        $purchase = $purchaseDetail->purchase;
        $purchase->amount = $purchase->details->sum(function ($detail) {
            return round($detail->cost * $detail->quantity, 2);
        });
        $purchase->save();
    }

    private function updatePurchaseStatus(PurchaseDetail $purchaseDetail)
    {
        if($purchaseDetail->quantity_received == 0){
            $purchaseDetail->status = StatusPurchaseDetailEnum::pendiente;
        }

        if($purchaseDetail->quantity_received > 0 && $purchaseDetail->quantity_received != $purchaseDetail->quantity)
        {
            $purchaseDetail->status = StatusPurchaseDetailEnum::parcial;
        }

        if($purchaseDetail->quantity == $purchaseDetail->quantity_received ){
            $purchaseDetail->status = StatusPurchaseDetailEnum::surtida;
        }
        $purchaseDetail->save();
        return;
    }

}
