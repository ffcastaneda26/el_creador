<?php

use App\Models\OrdenFabricacion;
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
        Schema::create('cabezas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrdenFabricacion::class);
            $table->string('modelo',20)->nullable()->default(null)->comment('Modelo');
            $table->string('tamano',20)->nullable()->default(null)->comment('Tamaño');
            $table->boolean('fibra')->default(0)->comment('fibra');
            $table->boolean('polyfoam')->default(0)->comment('Polifoam');
            $table->boolean('full_print')->default(0)->comment('Full Print');
            $table->boolean('sistema_ventilacion')->default(0)->comment('Sistema Ventilación');
            $table->boolean('pila')->default(0)->comment('Pila');
            $table->boolean('cargador')->default(0)->comment('Cargador');
            $table->mediumText('observaciones')->nullable()->default(null)->comment('Observaciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabezas');
    }
};
