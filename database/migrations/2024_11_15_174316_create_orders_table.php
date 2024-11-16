<?php

use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->comment('Cliente');
            $table->timestamp('date')->comment('Fecha de ordern de compra');
            $table->boolean('approved')->default(0)->comment('¿Aprobada?');
            $table->timestamp('date_approved')->nullable()->comment('Aprobada el día');
            $table->decimal('advance',8,2)->default(0)->comment('Anticipo');
            $table->decimal('pending_balance',8,2)->default(0)->comment('Anticipo');
            $table->decimal('subtotal',8,2)->default(0)->comment('Subtotal');
            $table->decimal('tax',8,2)->default(0)->comment('Iva');
            $table->decimal('discount',8,2)->default(0)->comment('Descuento');
            $table->decimal('total',8,2)->default(0)->comment('Total');
            $table->timestamp('delivery_date')->nullable()->comment('Fecha promesa de entrega');
            $table->string('address',100)->nullable()->default(null)->comment('Calle, Número');
            $table->string('colony',100)->nullable()->default(null)->comment('Colonia');
            $table->mediumText('references')->nullable()->default(null)->comment('Referencias');
            $table->string('zipcode',5)->nullable()->default(null)->comment('Código Postal');
            $table->foreignIdFor(Country::class)->nullable()->default(null)->comment('País');
            $table->foreignIdFor(State::class)->nullable()->default(null)->comment('Entidad Federativa');
            $table->foreignIdFor(Municipality::class)->nullable()->default(null)->comment('Municipio');
            $table->foreignIdFor(City::class)->nullable()->default(null)->comment('Ciudad');
            $table->mediumText('notes')->nullable()->default(null)->comment('Notas');
            $table->boolean('require_invoice')->default(1)->comment('¿Requiere Factura?');
            $table->date('payment_promise_date')->nullable()->default(null)->comment('Fecha promesa de pago');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
