<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre completo');
            $table->string('email')->nullable()->default(null)->comment('Correo electrónico');
            $table->string('phone',15)->nullable()->default(null)->comment('Teléfono');
            $table->string('rfc',13)->nullable()->default(null)->comment('Registro Federal de Contribuyentes');
            $table->enum('type',['Física','Moral'])->default('Física')->comment('Régimen Fiscal');
            $table->string('address',100)->nullable()->default(null)->comment('Calle, Número');
            $table->string('colony',100)->nullable()->default(null)->comment('Colonia');
            $table->mediumText('references')->nullable()->default(null)->comment('Referencias');
            $table->string('zipcode',5)->nullable()->default(null)->comment('Código Postal');
            $table->foreignIdFor(Country::class)->nullable()->default(null)->comment('País');
            $table->foreignIdFor(State::class)->nullable()->default(null)->comment('Entidad Federativa');
            $table->foreignIdFor(Municipality::class)->nullable()->default(null)->comment('Municipio');
            $table->foreignIdFor(City::class)->nullable()->default(null)->comment('Ciudad');
            $table->mediumText('notes')->nullable()->default(null)->comment('Notas');
            $table->boolean('active')->default(1)->comment('¿Está activo?');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
