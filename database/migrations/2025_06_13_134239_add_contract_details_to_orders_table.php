<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('motley_name', 40)->nullable()->after('payment_promise_date')->comment('Nombre Botarga');
            $table->string('folio', 10)->nullable()->default(null)->after('motley_name')->comment('Folio');
            $table->string('phone_whatsApp', 15)->nullable()->default(null)->after('folio')->comment('Número WhatsApp');
            $table->tinyInteger('days_term')
                ->nullable()->default(null)
                ->after('phone_whatsApp')
                ->comment('Plazo para entrega en días');
            $table->string('shipping_company', 100)->nullable()->default(null)->after('days_term')->comment('Empresa Envío');
            $table->string('shipping_company_address', 150)->nullable()->default(null)->after('shipping_company')->comment('Domicilio Empresa Envío');
            $table->decimal('shipping_cost', 8, 2)->nullable()->default(null)->after('shipping_company_address')->comment('Costo Envío');

        });
    }

    /**
     * Reverse the migrations.
     * Se agregan atributos adicionales para el contrato a la tabla "orders" = "Órdenes de Compra"
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('motley_name');
            $table->dropColumn('folio');
            $table->dropColumn('phone_whatsApp');
            $table->dropColumn('days_term');
            $table->dropColumn('shipping_company');
            $table->dropColumn('shipping_company_address');
            $table->dropColumn('shipping_cost');
        });
    }
};
