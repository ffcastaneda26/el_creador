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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name',100)->comment('Nombre');
            $table->string('short',20)->comment('Corto');
            $table->string('email')->nullable()->default(null)->comment('Correo Electrónico');
            $table->string('phone',15)->nullable()->default(null)->comment('Teléfono');
            $table->string('rfc',13)->nullable()->default(null)->comment('RFC');
            $table->boolean('active')->default(1)->comment('¿Está activo?');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
