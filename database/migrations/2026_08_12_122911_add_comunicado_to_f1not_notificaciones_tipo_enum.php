<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('f1not_notificaciones', 'tipo')) {
            DB::statement('ALTER TABLE f1not_notificaciones DROP COLUMN tipo');
        }

        Schema::table('f1not_notificaciones', function (Blueprint $table) {
            $table->enum('tipo', [
                'convocatoria',
                'acuerdo',
                'mensaje',
                'sistema',
                'actividad_asignada',
                'evidencia_subida',
                'actividad_concluida',
                'comunicado',
            ])->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('f1not_notificaciones', 'tipo')) {
            DB::statement('ALTER TABLE f1not_notificaciones DROP COLUMN tipo');
        }

        Schema::table('f1not_notificaciones', function (Blueprint $table) {
            $table->enum('tipo', [
                'convocatoria',
                'acuerdo',
                'mensaje',
                'sistema',
                'actividad_asignada',
                'evidencia_subida',
                'actividad_concluida',
            ])->after('user_id');
        });
    }
};