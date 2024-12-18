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
        Schema::table('key_movements', function (Blueprint $table) {
           $table->boolean('is_purchase')->default(0)->after('require_cost')->comment('¿Es Compra?');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('key_movements', function (Blueprint $table) {
            $table->dropColumn('is_purchase');
        });
    }
};
