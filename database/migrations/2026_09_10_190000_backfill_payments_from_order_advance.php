<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * A partir de ahora el anticipo de un pedido es la suma de sus pagos registrados.
 * Los pedidos que ya traian un anticipo capturado a mano no tienen ningun pago que
 * lo respalde, asi que se les crea uno para no perder el saldo historico.
 */
return new class extends Migration
{
    public function up(): void
    {
        $defaultMethodId = DB::table('payment_methods')->orderBy('id')->value('id');

        if ($defaultMethodId === null) {
            return;
        }

        $orders = DB::table('orders')
            ->where('advance', '>', 0)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('payments')
                    ->whereColumn('payments.order_id', 'orders.id');
            })
            ->get(['id', 'client_id', 'advance', 'date', 'created_at']);

        foreach ($orders as $order) {
            DB::table('payments')->insert([
                'client_id'         => $order->client_id,
                'order_id'          => $order->id,
                'payment_method_id' => $defaultMethodId,
                'date'              => $order->date ?? $order->created_at ?? now(),
                'amount'            => $order->advance,
                'reference'         => 'Anticipo registrado antes del control de pagos',
                'notes'             => 'Generado automaticamente para respaldar el anticipo capturado en el pedido.',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // Deja todos los pedidos con el anticipo y el saldo que corresponden a sus
        // pagos reales. Hay pedidos antiguos con saldo en cero pese a no tener nada
        // cobrado, y ese numero es el que se imprime en el contrato.
        \App\Models\Order::query()->each(fn (\App\Models\Order $order) => $order->recalculateBalance());
    }

    public function down(): void
    {
        DB::table('payments')
            ->where('reference', 'Anticipo registrado antes del control de pagos')
            ->delete();
    }
};
