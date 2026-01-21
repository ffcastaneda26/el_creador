<?php

use App\Enums\ClientTypeEnum;
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
        if (Schema::hasColumn('clients', 'type')) {
            return;
        }
        Schema::table('clients', function (Blueprint $table) {
            $table->enum('type', array_column(ClientTypeEnum::cases(), 'value'))
                ->default(ClientTypeEnum::fisica->value)
                ->comment('Regimen Fiscal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('clients', 'type')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
