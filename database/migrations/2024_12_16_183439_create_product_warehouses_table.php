<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
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
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class)->comment('Almacén');
            $table->foreignIdFor(Product::class)->comment('Producto');
            $table->decimal('price',8,2)->default(0.00)->comment('Precio de venta');
            $table->decimal('last_purchase_price',11,6)->default(0.00)->comment('Precio última compra');
            $table->integer('stock')->default(0)->comment('Existencia Total');
            $table->integer('stock_available')->default(0)->comment('Existencia disponible');
            $table->integer('stock_compromised')->default(0)->comment('Existencia Comprometida');
            $table->integer('stock_min')->default(0)->comment('Existencia minima');
            $table->integer('stock_max')->default(0)->comment('Existencia máxima');
            $table->integer('stock_reorder')->default(0)->comment('Punto de reorden');
            $table->decimal('average_cost',11,6)->default(0.00)->comment('Costo Promedio');
            $table->boolean('active')->default(1)->comment('¿Activo?');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
    }
};
