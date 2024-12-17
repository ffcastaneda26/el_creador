<?php

use App\Models\Unit;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',150)->unique()->comment('Nombre');
            $table->string('code',30)->unique()->nullable()->default(null)->comment('Código');
            $table->foreignIdFor(Unit::class)->nullable()->default(null)->comment('Unidad de Medida');
            $table->mediumText('description')->nullable()->comment('Descripción');
            // $table->decimal('price',8,2)->default(0.00)->comment('Precio Unitario');
            // $table->decimal('last_purchase_price',11,6)->default(0.00)->comment('Precio última compra');
            // $table->integer('stock')->default(0)->comment('Existencia Total');
            // $table->integer('stock_min')->default(0)->comment('Existencia Mínima');
            // $table->integer('stock_max')->default(0)->comment('Existencia máxima');
            // $table->integer('stock_reorder')->default(0)->comment('Punto de reorden');
            // $table->decimal('average_cost',11,6)->default(0.00)->comment('Costo Promedio');
            $table->string('image')->nullable()->comment('Imagen');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
