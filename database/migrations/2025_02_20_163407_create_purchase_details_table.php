<?php

use App\Models\Product;
use App\Models\Purchase;
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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class)->constrained()->cascadeOnDelete()->comment('Orden de compra');
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete()->comment('Producto');
            $table->integer('quantity')->comment('Cantidad');
            $table->integer('quantity_received')->default(0)->comment('Cantidad entregada');
            $table->decimal('cost', 8, 2)->default(0)->comment('Costo');

            $table->enum('status', ['pendiente','surtida', 'parcial'])->default('pendiente')->comment('Estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
