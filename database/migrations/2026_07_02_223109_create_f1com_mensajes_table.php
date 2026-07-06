<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f1com_mensajes", function (Blueprint $table) {
            $table->id();
            $table->foreignId("conversacion_id")
                  ->constrained("f1com_conversaciones")
                  ->cascadeOnDelete();
            $table->foreignId("emisor_id")->constrained("users");
            $table->text("contenido");
            $table->timestamps();

            // El historial siempre se consulta filtrado y ordenado por conversacion
            $table->index(["conversacion_id", "created_at"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f1com_mensajes");
    }
};