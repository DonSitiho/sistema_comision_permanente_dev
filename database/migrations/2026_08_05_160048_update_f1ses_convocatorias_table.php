<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('f1ses_convocatorias', 'tipo_conv')) {
            DB::statement('ALTER TABLE f1ses_convocatorias DROP COLUMN tipo_conv');
        }

        Schema::table('f1ses_convocatorias', function (Blueprint $table) {
            $table->enum('rol_convocante', ['secretario_tecnico', 'enlace'])->after('lugar');
            $table->enum('naturaleza', ['ordinaria', 'extraordinaria'])->nullable()->after('rol_convocante');
            $table->enum('ambito', ['regional', 'multi_region', 'municipal'])->after('naturaleza');

            $table->index('ambito');
        });
    }

    public function down(): void
    {
        Schema::table('f1ses_convocatorias', function (Blueprint $table) {
            $table->dropIndex(['ambito']);
            $table->dropColumn(['rol_convocante', 'naturaleza', 'ambito']);
        });

        DB::statement('ALTER TABLE f1ses_convocatorias ADD tipo_conv ENUM("ordinaria", "extra ordinaria", "regional") NULL');
    }
};