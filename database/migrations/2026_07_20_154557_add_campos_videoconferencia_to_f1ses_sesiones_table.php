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
        Schema::table('f1ses_sesiones', function (Blueprint $table) {
             //Nuevos campos agregados
            $table->text("descripcion_sesion"); //Descripción de la sesión
            $table->time("hora_inicio"); //Hora de inicio de la video conferencia
            $table->time("hora_fin"); //Hora de finalización de la video conferencia
            $table->string("cod_acceso_videoconf", 10)->nullable(); // Código de acceso de la video conferencia
            $table->unsignedBigInteger("num_enlace_videoconf")->nullable(); // ID enlace de la video conferencia
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('f1ses_sesiones', function (Blueprint $table) {
            $table->dropColumn([
                'descripcion_sesion',
                'hora_inicio',
                'hora_fin',
                'cod_acceso_videoconf',
                'num_enlace_videoconf',
            ]);
        });
    }
};
