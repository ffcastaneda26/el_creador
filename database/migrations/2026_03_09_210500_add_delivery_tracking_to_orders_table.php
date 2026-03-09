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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('delivered')
                ->default(false)
                ->after('delivery_date')
                ->comment('Indica si la botarga ya fue entregada');

            $table->timestamp('delivered_at')
                ->nullable()
                ->after('delivered')
                ->comment('Fecha y hora de entrega confirmada');

            $table->foreignId('delivered_by')
                ->nullable()
                ->after('delivered_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('delivered_by');
            $table->dropColumn(['delivered_at', 'delivered']);
        });
    }
};
