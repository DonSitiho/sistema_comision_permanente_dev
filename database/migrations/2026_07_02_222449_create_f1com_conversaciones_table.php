<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f1com_conversaciones", function (Blueprint $table) {
            $table->id();
            $table->enum("tipo", ["individual", "grupo"]);
            $table->string("nombre", 150)->nullable(); // solo aplica a grupos
            $table->foreignId("creado_por")->constrained("users");
            // Nullable: el grupo puede nacer de una sesion o ser independiente
            $table->foreignId("sesion_origen_id")
                  ->nullable()
                  ->constrained("f1ses_sesiones")
                  ->nullOnDelete();
            // clave_par = los dos IDs de usuario ordenados (menor-mayor), unica.
            // Es lo que permite reutilizar el hilo 1:1 en vez de duplicarlo.
            $table->string("clave_par", 40)->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f1com_conversaciones");
    }
};