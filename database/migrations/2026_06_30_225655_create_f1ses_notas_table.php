<?php
// database/migrations/2026_06_30_225655_create_f1ses_notas_table.php

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
        Schema::create('f1ses_notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sesion_id")->constrained("f1ses_sesiones")
                  ->cascadeOnDelete(); 
            $table->foreignId("autor_id")->constrained("users"); 
            $table->enum("tipo", ["nota", "comentario"]); 
            $table->text("contenido"); 
            $table->timestamps(); 
  
            $table->index(["sesion_id", "tipo"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1ses_notas');
    }
};
