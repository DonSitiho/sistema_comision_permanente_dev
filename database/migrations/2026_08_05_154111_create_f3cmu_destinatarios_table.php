<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f3cmu_destinatarios", function (Blueprint $table) {
            $table->id();
            $table->foreignId("comunicado_id")
                  ->constrained("f3cmu_comunicados")
                  ->cascadeOnDelete();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->timestamp("leido_at")->nullable();
            $table->timestamp("aceptado_at")->nullable(); // solo obligatorios
            $table->timestamps();
            $table->unique(["comunicado_id", "user_id"]);
            $table->index("user_id");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f3cmu_destinatarios");
    }
};