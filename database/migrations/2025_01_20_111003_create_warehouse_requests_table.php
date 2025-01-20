<?php

use App\Models\User;
use App\Models\Warehouse;
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
        Schema::create('warehouse_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class)->comment('Almacén');
            $table->string('folio',15)->unique()->comment('Folio Interno');
            $table->date('date')->nullable()->default(null)->comment('Fecha Solicitud');
            $table->string('reference',30)->nullable()->default(null)->comment('Referencia');
            $table->mediumText('notes')->nullable()->default(null)->comment('Notas');
            $table->foreignIdFor(User::class)->comment('Usuario que crea o modifica');
            $table->unsignedBigInteger('user_auhtorizer_id')->nullable()->default(null)->comment('Usuario que autoriza');
            $table->enum('status', ['abierto', 'autorizado','parcial','surtido','cancelado'])->comment('Estado');
            $table->foreign('user_auhtorizer_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_requests');
    }
};
