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
            $table->boolean('iva')->default(1)->after('tax_type')->comment('¿Iva?');
            $table->boolean('retencion')->default(value: 1)->after('iva')->comment('¿Retención?');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
           $table->dropColumn('iva');
           $table->dropColumn('retencion');

        });
    }
};
