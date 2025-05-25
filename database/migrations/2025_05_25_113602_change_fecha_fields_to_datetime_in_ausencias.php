<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ausencias', function (Blueprint $table) {
            $table->dateTime('fecha_inicio')->change();
            $table->dateTime('fecha_fin')->change();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ausencias', function (Blueprint $table) {
            //
        });
    }
};
