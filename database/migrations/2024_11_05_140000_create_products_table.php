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
            $table->string('sku',30)->nullable()->default(null)->comment('Código de Barras');
            $table->foreignIdFor(Unit::class)->nullable()->default(null)->comment('Unidad de Medida');
            $table->mediumText('description')->nullable()->comment('Descripción');
            $table->string('image')->nullable()->comment('Imagen');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
            $table->boolean('active')->default(1)->comment('¿Activo?');
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
