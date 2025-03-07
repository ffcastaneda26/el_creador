<?php

use App\Enums\Enums\StatusReceiptEnum;
use App\Models\Purchase;
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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Purchase::class)->comment('Orden de compra');
            $table->string('folio',20)->nullable()->default(null)->comment('Folio interno');
            $table->date('date')->nullable()->default(null)->comment('Fecha Recepción');
            $table->decimal('amount',10,2)->default(0)->comment('Importe');
            $table->string('reference')->nullable()->default(null)->comment('Referencia');
            $table->mediumText('notes')->nullable()->default(null)->comment('Notas');
            $table->foreignIdFor(User::class)->comment('Usuario que crea o modifica');
            $table->unsignedBigInteger('user_authorizer_id')->nullable()->default(null)->comment('Usuario que autoriza');
            $table->enum('status', array_column(StatusReceiptEnum::cases(), 'value'))->default(StatusReceiptEnum::abierto->value);
            $table->foreign('user_authorizer_id')->references('id')->on('users');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
