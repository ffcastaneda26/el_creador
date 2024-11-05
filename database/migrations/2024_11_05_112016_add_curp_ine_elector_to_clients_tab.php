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
            $table->string('curp',  18)->nullable()->default(null)->after('mobile')->comment('CURP');
            $table->string('ine',13)->nullable()->default(null)->after('curp')->comment('INE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('curp');
            $table->dropColumn('ine');
        });
    }
};
