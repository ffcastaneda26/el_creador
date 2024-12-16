<?php

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
        Schema::create('key_movements', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->comment('Nombre');
            $table->string('short',6)->comment('Corto');
            $table->string('used_to',10)->comment('Usarse para: I=Inventory S=Sale');
            $table->string('type')->comment('Tipo: I=Input O=Output');
            $table->boolean('require_cost')->default(1)->comment('¿Requerir costo en movimiento?');
            $table->foreignIdFor(User::class)->comment('Usuario creó o modificó');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_movements');
    }
};
