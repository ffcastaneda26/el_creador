<?php

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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Country::class)->default(135)->comment('País');
            $table->foreignIdFor(State::class)->comment('Entidad Federativa');
            $table->foreignIdFor(Municipality::class)->comment('Municipio');
            $table->string('name',100)->comment('Ciudad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
