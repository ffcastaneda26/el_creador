<?php

use App\Models\Provider;
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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Provider::class)->comment('Proveedor');
            $table->string('folio',15)->unique()->comment('Folio Interno');
            $table->date('date')->nullable()->default(null)->comment('Fecha Solicitud');
            $table->decimal('amount',10,2)->default(0)->comment('Importe');
            $table->mediumText('notes')->nullable()->default(null)->comment('Notas');
            $table->foreignIdFor(User::class)->comment('Usuario que crea o modifica');
            $table->unsignedBigInteger('user_authorizer_id')->nullable()->default(null)->comment('Usuario que autoriza');
            $table->enum('status', ['abierto', 'autorizado','parcial','surtido','cancelado'])->comment('Estado');
            $table->foreign('user_authorizer_id')->references('id')->on('users');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
