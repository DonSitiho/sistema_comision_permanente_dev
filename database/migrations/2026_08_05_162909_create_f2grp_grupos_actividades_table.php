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
        Schema::create('f2grp_grupos_actividades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('dueno_id')->constrained('users');
            $table->foreignId('convocatoria_id')->nullable()
                ->constrained('f1ses_convocatorias')->nullOnDelete();
            $table->foreignId('acuerdo_id')->nullable()
                ->constrained('f1acu_acuerdos')->nullOnDelete();
            $table->enum('estatus', ['pendiente', 'en_proceso', 'terminado'])->default('pendiente');
            $table->boolean('estatus_manual')->default(false);
            $table->timestamps();
            //$table->index(['dueno_id', 'convocatoria_id', 'acuerdo_id']);
            $table->index(['dueno_id']);
            $table->index(['convocatoria_id']);
            $table->index(['acuerdo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f2grp_grupos_actividades');
    }
};
