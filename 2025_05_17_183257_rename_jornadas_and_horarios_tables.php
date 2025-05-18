<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('jornadas', 'registro_jornada');
        Schema::rename('horarios', 'sesiones_lectivas');
    }

    public function down(): void
    {
        Schema::rename('registro_jornada', 'jornadas');
        Schema::rename('sesiones_lectivas', 'horarios');
    }
};
