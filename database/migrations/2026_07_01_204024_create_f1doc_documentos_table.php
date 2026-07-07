<?php
// database/migrations/2026_07_01_204024_create_f1doc_documentos_table.php

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
        Schema::create('f1doc_documentos', function (Blueprint $table) {
            $table->id();
             $table->morphs("documentable"); 
  
            $table->string("nombre_original", 255); 
            $table->string("ruta_cifrada", 500); 
            $table->string("mime_type", 100)->nullable(); 
            $table->unsignedBigInteger("tamano")->default(0); 
  
            $table->foreignId("subido_por")->constrained("users"); 
            $table->string("categoria", 50)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1doc_documentos');
    }
};
