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
        Schema::create('cotization_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotization_id')->constrained()->onDelete('cascade')->comment('Referencia a la cotización');
            $table->string('name')->comment('Nombre');
            $table->integer('quantity')->comment('Cantidad');
            $table->decimal('price', 8, 2)->comment('Precio unitario');
            $table->text('description')->nullable()->comment('Descripción');
            $table->string('image')->nullable()->comment('Imagen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotization_details');
    }
};
