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
        Schema::create('f1ses_asistentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sesion_id")->constrained("f1ses_sesiones")
                  ->cascadeOnDelete(); 
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete(); 
            $table->boolean("asistio")->default(false); 
            $table->string("rol_en_sesion", 50)->nullable(); 
            $table->timestamps(); 
  
            $table->unique(["sesion_id", "user_id"]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1ses_asistentes');
    }
};
