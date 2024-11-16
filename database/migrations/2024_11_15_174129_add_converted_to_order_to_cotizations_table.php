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
        Schema::table('cotizations', function (Blueprint $table) {
            $table->boolean('converted_to_order')->default(0)->after('envio')->comment('¿Convertida a Orden de Compra?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizations', function (Blueprint $table) {
            $table->dropColumn('converted_to_order');
        });
    }
};
