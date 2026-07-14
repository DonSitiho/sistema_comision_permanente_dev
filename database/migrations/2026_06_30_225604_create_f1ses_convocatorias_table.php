<?php
// database/migrations/2026_06_30_225604_create_f1ses_convocatorias_table.php

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
        Schema::create('f1ses_convocatorias', function (Blueprint $table) {
            $table->id();
            $table->string("folio", 30)->unique(); 
            $table->foreignId("creada_por")->constrained("users"); 
            $table->string("titulo", 200); 
            $table->text("descripcion")->nullable(); 
            $table->dateTime("fecha_sesion"); 
            $table->string("lugar", 255)->nullable(); 
  
            $table->enum("estado", ["borrador", "enviada", "cerrada","cancelada","pospuesta"]) 
                  ->default("borrador"); 
            $table->index("estado");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1ses_convocatorias');
    }
};
