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
        Schema::create('f2evi_evidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')
                ->constrained('f2act_actividades')->cascadeOnDelete();
            $table->enum('tipo', ['archivo', 'url']);
            $table->foreignId('documento_id')->nullable()
                ->constrained('f1doc_documentos')->nullOnDelete();
            $table->string('url')->nullable();
            $table->foreignId('subida_por')->constrained('users');
            $table->timestamps();
            $table->index('actividad_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f2evi_evidencias');
    }
};
