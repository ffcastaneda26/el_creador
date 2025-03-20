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
            $table->decimal('retencion_isr',8,2)->after('envio')->default(0)->comment('Retención ISR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizations', function (Blueprint $table) {
            $table->dropColumn('retencion_isr'); // Elimina la columna 'fee'

        });
    }
};
