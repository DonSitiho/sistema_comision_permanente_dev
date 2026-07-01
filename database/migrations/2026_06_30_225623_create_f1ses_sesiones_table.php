<?php
// database/migrations/2026_06_30_225623_create_f1ses_sesiones_table.php

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
        Schema::create('f1ses_sesiones', function (Blueprint $table) {
            $table->id();
            $table->foreignId("convocatoria_id") 
                  ->nullable() 
                  ->constrained("f1ses_convocatorias") 
                  ->nullOnDelete(); 
  
            $table->enum("tipo", ["presencial", "virtual", "mixta"]); 
  
            $table->enum("estado", ["convocada", "en_curso", "realizada", "cancelada"]) 
                  ->default("convocada"); 
  
            $table->string("enlace_videoconf", 500)->nullable(); 
            $table->enum("plataforma", ["zoom", "meet", "webex", "teams", "otro"]) 
                  ->nullable(); 
  
            $table->unsignedBigInteger("videoconf_proveedor_id")->nullable(); 
            $table->string("videoconf_meeting_id", 255)->nullable(); 
            $table->json("videoconf_metadata")->nullable(); 
            $table->boolean("videoconf_sincronizado")->default(false); 
  
            $table->foreignId("creada_por")->constrained("users");
  
            $table->index("estado"); 
            $table->index("tipo"); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1ses_sesiones');
    }
};
