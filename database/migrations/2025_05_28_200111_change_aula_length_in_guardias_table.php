<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guardias', function (Blueprint $table) {
            $table->string('aula', 50)->change(); // Aumenta de 10 a 50 caracteres
        });
    }

    public function down(): void
    {
        Schema::table('guardias', function (Blueprint $table) {
            $table->string('aula', 10)->change(); // Reversión si se hace rollback
        });
    }
};
