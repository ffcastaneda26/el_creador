<?php

use App\Models\Product;
use App\Models\ProductWarehouse;
use App\Models\WarehouseRequest;
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
        Schema::create('warehouse_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(WarehouseRequest::class)->constrained()->cascadeOnDelete()->comment('Solicitud de Almacén');
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete()->comment('Producto');
            $table->integer('quantity')->comment('Cantidad');
            $table->integer('quantity_delivered')->default(0)->comment('Cantidad entregada');
            $table->enum('status', ['pendiente','surtida', 'parcial'])->default('pendiente')->comment('Estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_request_details');
    }
};
