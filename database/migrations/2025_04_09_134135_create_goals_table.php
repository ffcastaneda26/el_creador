<?php

use App\Enums\Enums\GoalMPeriodEnum;
use App\Enums\Enums\GoalPeriodEnum;
use App\Models\Metric;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.ad
     */
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Metric::class)->constrained()->onDelete('cascade')->comment('Métrica');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade')->comment('Usuario');
            $table->enum('period', array_column(GoalPeriodEnum::cases(), 'value')); // Usa los valores del enum
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('goal_units')->nullable();
            $table->decimal('goal_amount', 9, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
