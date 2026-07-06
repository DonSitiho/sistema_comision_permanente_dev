<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f1com_participantes", function (Blueprint $table) {
            $table->id();
            $table->foreignId("conversacion_id")
                  ->constrained("f1com_conversaciones")
                  ->cascadeOnDelete();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();

            // Para calcular mensajes no leidos: cuenta los posteriores a esta fecha
            $table->timestamp("ultimo_leido_at")->nullable();

            // Solo relevante en grupos: puede agregar/quitar participantes
            $table->boolean("es_admin")->default(false);

            $table->timestamps();
            $table->unique(["conversacion_id", "user_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f1com_participantes");
    }
};