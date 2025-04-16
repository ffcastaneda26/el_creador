<?php

use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Hola
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->constrained()->cascadeOnDelete()->comment('Cliente');
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete()->comment('Orden de Compra');
            $table->foreignIdFor(PaymentMethod::class)->constrained()->cascadeOnDelete()->comment('Método de pago');
            $table->date('date')->comment('Fecha de pago');
            $table->decimal('amount', 10, 2)->comment('Importe');
            $table->string('reference',50)->nullable()->comment('Referencia');
            $table->string('reference_number',20)->nullable()->comment('Número de tarjeta');
            $table->string('voucher_image')->nullable()->comment('Archivo del comprobante');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
