<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f1com_conversaciones", function (Blueprint $table) {
            $table->id();

            $table->enum("tipo", ["individual", "grupal"]);
            $table->string("nombre", 150)->nullable(); // solo grupal

            // Nullable: el grupo puede nacer de una sesion o ser independiente
            $table->foreignId("sesion_id")
                  ->nullable()
                  ->constrained("f1ses_sesiones")
                  ->nullOnDelete();

            $table->foreignId("creada_por")->constrained("users");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f1com_conversaciones");
    }
};