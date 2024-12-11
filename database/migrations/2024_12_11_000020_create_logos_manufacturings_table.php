<?php

use App\Models\Anexo;
use App\Models\Manufacturing;
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
        Schema::create('logos_manufacturing', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Manufacturing::class);
            $table->foreignIdFor(Anexo::class)->comment('Id de Anexo');
            $table->string('ubicacion',30)->nullable()->default(null)->comment('Ubicación');
            $table->string('material',30)->nullable()->default(null)->comment('Material');
            $table->string('tamano',30)->nullable()->default(null)->comment('Material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logos_manufacturing');
    }
};
