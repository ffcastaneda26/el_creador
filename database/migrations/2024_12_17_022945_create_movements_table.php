<?php

use App\Models\KeyMovement;
use App\Models\Product;
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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warehouse::class)->comment('Almacen');
            $table->foreignIdFor(Product::class)->comment('Producto');
            $table->foreignIdFor(KeyMovement::class)->comment('Clave de movimiento');
            $table->date('date')->comment('Fecha');
            $table->integer('quantity')->comment('Cantidad');
            $table->decimal('cost',11,6)->default(0.00)->comment('Costo');
            $table->string('reference',100)->nullable()->default(null)->comment('Referencia');
            $table->mediumText('notes')->nullable()->default(null)->comment('Notas');
            $table->string('voucher_image')->nullable()->default(null)->comment('Imagen del comprobante');
            $table->string('status',20)->nullable()->default(null)->comment('Estado');
            $table->foreignIdFor(User::class)->comment('Usuario que creó o modificó');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
