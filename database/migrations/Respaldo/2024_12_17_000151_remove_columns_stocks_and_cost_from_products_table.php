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
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // $table->dropColumn('price');
            $table->dropColumn('last_purchase_price');
            $table->dropColumn('stock');
            $table->dropColumn('stock_min');
            $table->dropColumn('stock_max');
            $table->dropColumn('stock_reorder');
            $table->dropColumn('average_cost');
        });
    }

};
