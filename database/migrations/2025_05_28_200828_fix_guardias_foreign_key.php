<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::table('guardias', function (Blueprint $table) {
        $table->unsignedBigInteger('docente_id_temp')->nullable()->after('docente_id');
    });
}

public function down()
{
    Schema::table('guardias', function (Blueprint $table) {
        $table->dropColumn('docente_id_temp');
    });
}

};
