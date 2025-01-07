<?php

use App\Models\Manufacturing;
use App\Models\Product;
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
        Schema::create('manufacturing_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Manufacturing::class)->comment('Orden Fabricación');
            $table->foreignIdFor(Product::class)->comment('Producto');
            $table->integer('quantity')->comment('Cantidad');
            $table->integer('assorted')->default(0)->comment('Cantidad Surtida');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_products');
    }
};
