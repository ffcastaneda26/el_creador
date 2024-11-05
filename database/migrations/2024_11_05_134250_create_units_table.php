<?php

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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->comment('Nombre');
            $table->string('symbol',10)->nullable()->default(null)->comment('Símbolo');
            $table->enum('type',['Cantidad','Blister','Area','Longitud','Masa','Potencia','Presión','Temperatura','Tiempo','Tipo','Voltaje','Volumen'])->default('Area')->comment('Tipo');
            $table->text('description')->nullable()->default(null)->comment('Descripción');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
