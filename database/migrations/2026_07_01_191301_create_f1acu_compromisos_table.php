<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f1acu_compromisos", function (Blueprint $table) {
            $table->id();
            $table->foreignId("acuerdo_id")->constrained("f1acu_acuerdos")->cascadeOnDelete();
            $table->foreignId("responsable_id")->constrained("users");
            $table->text("descripcion");
            $table->date("fecha_limite")->nullable();
            $table->enum("estado", ["pendiente", "en_proceso", "cumplido"])
                ->default("pendiente");
            $table->timestamps();
            $table->index(["responsable_id", "estado"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f1acu_compromisos");
    }
};