<?php

use App\Models\Client;
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
        Schema::create('cotizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->comment('Cliente');
            $table->timestamp('fecha')->comment('Fecha de la cotización');
            $table->timestamp('vigencia')->nullable()->comment('Vigencia hasta el día');
            $table->boolean('aprobada')->default(0)->comment('¿Aprobado por cliente');
            $table->timestamp('fecha_aprobada')->nullable()->comment('Aprobada el día');
            $table->mediumText('description')->comment('Descripción');
            $table->decimal('subtotal',8,2)->default(0)->comment('Subtotal');
            $table->decimal('iva',8,2)->default(0)->comment('Iva');
            $table->decimal('descuento',8,2)->default(0)->comment('Descuento');
            $table->decimal('total',8,2)->default(0)->comment('Total');
            $table->timestamp('fecha_entrega')->nullable()->comment('Fecha promesa de entrega');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizations');
    }
};
