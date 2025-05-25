<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guardias', function (Blueprint $table) {
            $table->string('estado')->default('pendiente')->after('ausencia_id');
            // Puedes cambiar 'string' por enum si quieres estados fijos
            // $table->enum('estado', ['pendiente', 'confirmada', 'finalizada', 'cancelada'])->default('pendiente')->after('ausencia_id');
        });
    }

    public function down(): void
    {
        Schema::table('guardias', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};

