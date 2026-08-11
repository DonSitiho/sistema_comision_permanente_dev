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
        Schema::create('f2act_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')
                ->constrained('f2grp_grupos_actividades')->cascadeOnDelete();
            $table->text('descripcion');
            $table->foreignId('responsable_id')->constrained('users');
            $table->date('fecha_limite')->nullable();
            $table->enum('estatus', ['pendiente', 'en_proceso', 'terminado'])->default('pendiente');
            $table->timestamps();
            $table->index(['grupo_id', 'responsable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f2act_actividades');
    }
};
