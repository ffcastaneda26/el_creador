<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $this->recalculate($payment->order_id);
    }

    public function updated(Payment $payment): void
    {
        // Si el pago se movio a otro pedido hay que recalcular ambos.
        $original = $payment->getOriginal('order_id');

        if ($original !== null && $original != $payment->order_id) {
            $this->recalculate($original);
        }

        $this->recalculate($payment->order_id);
    }

    public function deleted(Payment $payment): void
    {
        $this->recalculate($payment->order_id);
    }

    public function restored(Payment $payment): void
    {
        $this->recalculate($payment->order_id);
    }

    private function recalculate(?int $orderId): void
    {
        if ($orderId === null) {
            return;
        }

        Order::find($orderId)?->recalculateBalance();
    }
}
