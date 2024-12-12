<?php

use App\Models\Manufacturing;
use App\Models\Part;
use App\Models\User;
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
        Schema::create('manufacturing_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Manufacturing::class)->comment('Orden de fabricación');
            $table->foreignIdFor(Part::class)->comment('Parte Padre');
            $table->unsignedBigInteger('child_part_id')->references('id')->on('parts')->cascadeOnDelete();
            $table->string('color',30)->nullable()->default('Color');
            $table->string('material',30)->nullable()->default('Material');
            $table->string('value',30)->nullable()->default(null)->comment('Valor');
            $table->boolean('include')->default(0)->comment('¿Incluir o no?');
            $table->foreignIdFor(User::class)->nullable()->default(null)->comment('Usuario que creó o modificó');
            $table->foreign('child_part_id')->references('id')->on('parts')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_parts');
    }
};
