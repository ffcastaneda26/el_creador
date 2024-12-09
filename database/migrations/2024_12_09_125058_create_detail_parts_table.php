<?php

use App\Models\Part;
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
        Schema::create('detail_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Part::class)->comment('Parte');
            $table->unsignedBigInteger('child_part_id')->comment('Parte Hija');
            $table->string('color',30)->nullable()->default(null)->comment('Color');
            $table->string('material',30)->nullable()->default(null)->comment('Material');
            $table->string('value')->nullable()->default(null)->comment('Valor');
            $table->boolean('require')->default(0)->comment('Lleva o no?');
            $table->foreign('child_part_id')->references('id')->on('parts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_parts');
    }
};
