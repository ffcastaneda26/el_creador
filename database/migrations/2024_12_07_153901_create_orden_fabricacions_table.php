<?php

use App\Models\Order;
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
        Schema::create('ordenes_fabricacion', function (Blueprint $table) {
            $table->id();
            $table->integer('folio')->comment('Folio');
            $table->foreignIdFor(Order::class)->comment('Orden de Compra');
            $table->string('name')->comment('Nombre de la botarga');
            $table->bigInteger('asesor_id')->nullable()->default(null)->comment('Asesor');
            $table->date('fecha_inicio')->nullable()->default(null)->comment('Inicio de fabricación');
            $table->date('fecha_fin')->nullable()->default(null)->comment('Fin de Fabricación');
            $table->string('rostro_color',20)->nullable()->default(null)->comment('Color del rostro');
            $table->string('rostro_material',20)->nullable()->default(null)->comment('Material del rostro');
            $table->string('cejas_color',20)->nullable()->default(null)->comment('Color de las cejas');
            $table->string('cejas_material',20)->nullable()->default(null)->comment('Material de las cejas');
            $table->string('pestanas_color',20)->nullable()->default(null)->comment('Color de las pestañas');
            $table->string('pestanas_material',20)->nullable()->default(null)->comment('Material de las pestañas');
            $table->string('parpado_color',20)->nullable()->default(null)->comment('Color del párpado');
            $table->string('parpado_material',20)->nullable()->default(null)->comment('Material del párpadp');
            $table->string('pupila_color',20)->nullable()->default(null)->comment('Color de la pupila');
            $table->string('pupila_material',20)->nullable()->default(null)->comment('Material de la pupila');
            $table->string('iris_color',20)->nullable()->default(null)->comment('Color de la iris');
            $table->string('iris_material',20)->nullable()->default(null)->comment('Material del iris');
            $table->string('esclorotica_color',20)->nullable()->default(null)->comment('Color de la esclorotica');
            $table->string('esclorotica_material',20)->nullable()->default(null)->comment('Material del esclorotica');
            $table->string('brillo_color',20)->nullable()->default(null)->comment('Color del brillo');
            $table->string('brillo_material',20)->nullable()->default(null)->comment('Material del brillo');
            $table->string('nariz_color',20)->nullable()->default(null)->comment('Color de la nariz');
            $table->string('nariz_material',20)->nullable()->default(null)->comment('Material de la nariz');
            $table->string('fosas_color',20)->nullable()->default(null)->comment('Color de las fosas');
            $table->string('fosas_material',20)->nullable()->default(null)->comment('Material de las fosas');
            $table->string('labios_color',20)->nullable()->default(null)->comment('Color de los labios');
            $table->string('labios_material',20)->nullable()->default(null)->comment('Material de los labios');
            $table->string('boca_color',20)->nullable()->default(null)->comment('Color de la boca');
            $table->string('boca_material',20)->nullable()->default(null)->comment('Material de la boca');
            $table->string('dientes_color',20)->nullable()->default(null)->comment('Color de los dientes');
            $table->string('dientes_material',20)->nulosble()->default(null)->comment('Material de los dientes');
            $table->string('barba_color',20)->nullable()->default(null)->comment('Color de la barba');
            $table->string('barba_material',20)->nulosble()->default(null)->comment('Material de la barba');
            $table->string('bigote_color',20)->nullable()->default(null)->comment('Color del bigote');
            $table->string('bigote_material',20)->nulosble()->default(null)->comment('Material del bigote');
            $table->string('lengua_color',20)->nullable()->default(null)->comment('Color de la lengua');
            $table->string('lengua_material',20)->nulosble()->default(null)->comment('Material de la lengua');
            $table->string('cabello_color',20)->nullable()->default(null)->comment('Color del cabello');
            $table->string('cabello_material',20)->nulosble()->default(null)->comment('Material del cabello');
            $table->string('orejas_color',20)->nullable()->default(null)->comment('Color de las orejas');
            $table->string('orejas_material',20)->nulosble()->default(null)->comment('Material de las orejas');
            $table->string('lentes_color',20)->nullable()->default(null)->comment('Color de los lentes');
            $table->string('lentes_material',20)->nulosble()->default(null)->comment('Material de los lentes');
            $table->mediumText('cabeza_observaciones')->nullable()->default(null)->comment('Observaciones sobre cabeza');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_fabricacion');
    }
};
