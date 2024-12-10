<?php

use App\Models\Order;
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
        Schema::create('manufacturings', function (Blueprint $table) {
            $table->id();
            $table->integer('folio')->unique()->comment('Folio');
            $table->foreignIdFor(Order::class)->comment('Orden de Compra');
            $table->string('botarga')->comment('Nombre de la botarga');
            $table->unsignedBigInteger('asesor_id')->nullable()->default(null)->comment('Asesor');
            $table->date('fecha_inicio')->nullable()->default(null)->comment('Inicio de fabricación');
            $table->date('fecha_fin')->nullable()->default(null)->comment('Fin de Fabricación');
            $table->mediumText('observaciones_cabeza')->nullable()->default(null)->comment('Observaciones sobre cabeza');
            $table->mediumText('observaciones_cuerpo')->nullable()->default(null)->comment('Observaciones sobre cuerpo');
            $table->mediumText('observaciones_estructura')->nullable()->default(null)->comment('Observaciones sobre estructura');
            $table->mediumText('observaciones_body_interno')->nullable()->default(null)->comment('Observaciones sobre body interno');
            $table->mediumText('observaciones_outfit1')->nullable()->default(null)->comment('Observaciones sobre outfit 1');
            $table->mediumText('observaciones_outfit2')->nullable()->default(null)->comment('Observaciones sobre outfit 2');
            $table->mediumText('observaciones_zapatos')->nullable()->default(null)->comment('Observaciones sobre zapatos');
            $table->mediumText('observaciones_accesorios')->nullable()->default(null)->comment('Observaciones sobre accesorios');
            $table->mediumText('observaciones_logotipos')->nullable()->default(null)->comment('Observaciones sobre logotipos');
            $table->foreignIdFor(User::class)->comment('Usuario que creó y/o modificó');
            $table->timestamps();
            $table->foreign('asesor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturings');
    }
};
