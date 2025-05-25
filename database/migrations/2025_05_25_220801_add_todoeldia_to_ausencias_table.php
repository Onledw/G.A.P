<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ausencias', function (Blueprint $table) {
            $table->boolean('todoeldia')->default(true)->after('fecha_fin');
        });
    }

    public function down(): void
    {
        Schema::table('ausencias', function (Blueprint $table) {
            $table->dropColumn('todoeldia');
        });
    }
};
