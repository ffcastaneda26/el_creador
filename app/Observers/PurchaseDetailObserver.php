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


}
