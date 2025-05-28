<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guardias', function (Blueprint $table) {
            // Primero eliminamos la columna actual si existe
            if (Schema::hasColumn('guardias', 'docente_id')) {
                $table->dropColumn('docente_id');
            }

            // Luego añadimos la columna correcta como unsignedBigInteger
            $table->unsignedBigInteger('docente_id')->after('aula');

            // Y agregamos la foreign key correctamente
            $table->foreign('docente_id')
                ->references('id')
                ->on('docentes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('guardias', function (Blueprint $table) {
            // Quitamos la FK y la columna entera
            $table->dropForeign(['docente_id']);
            $table->dropColumn('docente_id');

            // Restauramos el tipo anterior
            $table->string('docente_id', 20);
        });
    }
};
