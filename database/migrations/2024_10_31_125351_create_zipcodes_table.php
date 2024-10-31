<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
use App\Models\TypeZipcode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * zipcode	state_id	municipality_id	city_id	State	Municipality	City	Name	Type

     */
    public function up(): void
    {
        Schema::create('zipcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Country::class)->default(135)->comment('Id País');
            $table->foreignIdFor(State::class)->comment('Id Entidad Federativa');
            $table->foreignIdFor(Municipality::class)->comment('Id Municipio');
            $table->foreignIdFor(City::class)->comment('Id Ciudad');
            $table->string('zipcode',5)->comment('Código Postal');
            $table->string('country')->nullable()->default(null)->comment('Nombre País');
            $table->string('state',50)->nullable()->default(null)->comment('Entidad Federativa');
            $table->string('municipality',80)->nullable()->default(null)->comment('Municipio');
            $table->string('city',80)->nullable()->default(null)->comment('Ciudad');
            $table->string('name',100)->comment('Colonia, Barrio, etc');
            $table->foreignIdFor(TypeZipcode::class)->nullable()->default(1)->comment('Tupo de asentamiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zipcodes');
    }
};
