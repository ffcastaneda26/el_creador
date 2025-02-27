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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('street')->nullable()->default(null)->comment('Calle')->after('type');
            $table->string('number',5)->nullable()->default(null)->comment('Número')->after('street');
            $table->string('interior_number',5)->nullable()->default(null)->comment('Número Interior')->after('number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['number', 'street', 'interior_number']);
        });
    }
};
