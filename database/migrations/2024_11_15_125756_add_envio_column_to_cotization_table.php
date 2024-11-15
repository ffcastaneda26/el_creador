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
            $table->decimal('envio',8,2)->default(0)->after('descuento')->comment('Envío');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizations', function (Blueprint $table) {
            $table->dropColumn('envio');
        });
    }
};
