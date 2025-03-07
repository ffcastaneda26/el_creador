<?php

use App\Models\Product;
use App\Models\Receipt;
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
        Schema::create('receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Receipt::class)->comment('Id Recepción de material');
            $table->foreignIdFor(Product::class)->comment('Id producto recibido');
            $table->decimal('quantity',6,2)->comment('Cantidad recibida');
            $table->decimal('cost',8,2)->comment('Costo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipt_details');
    }
};
