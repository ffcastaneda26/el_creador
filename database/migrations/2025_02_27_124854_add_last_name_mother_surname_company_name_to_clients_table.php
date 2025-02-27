<?php

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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('last_name')->nullable()->default(null)->comment('Apellido Paterno')->after('name');
            $table->string('mother_surname')->nullable()->default(null)->comment('Apellido Materno')->after('last_name');
            $table->string('company_name')->nullable()->default(null)->comment('Nombre de la Empresa')->after('mother_surname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'mother_surname', 'company_name']);
        });
    }
};
