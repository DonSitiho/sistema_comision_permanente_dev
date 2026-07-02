<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f1acu_acuerdos", function (Blueprint $table) {
            $table->id();
            $table->foreignId("sesion_id")->constrained("f1ses_sesiones");
            $table->string("folio", 30)->unique();
            $table->text("descripcion");
            $table->enum("estado", ["registrado", "en_proceso", "cumplido", "cancelado"])
                ->default("registrado");
            $table->timestamps();
            $table->index("estado");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f1acu_acuerdos");
    }
};