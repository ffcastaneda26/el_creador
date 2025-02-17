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
        Schema::table('coverages', function (Blueprint $table) {
            $table->decimal('fee', 8, 2)->default(0)->after('distance')->comment('Tarifa'); // Agrega la columna 'fee'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coverages', function (Blueprint $table) {
            $table->dropColumn('fee'); // Elimina la columna 'fee'
        });
    }
};
