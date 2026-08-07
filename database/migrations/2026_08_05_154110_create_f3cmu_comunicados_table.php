<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("f3cmu_comunicados", function (Blueprint $table) {
            $table->id();
            $table->foreignId("categoria_id")->constrained("f3cmu_categorias");
            $table->foreignId("emitido_por")->constrained("users");
            $table->string("titulo", 200);
            $table->longText("cuerpo");
            $table->boolean("obligatorio")->default(false);
            // "region" se mantiene tal como dice la guia; en este proyecto
            // se resuelve contra dependencia_id (ver ComunicadoService).
            $table->enum("alcance", ["general", "region", "lista"]);
            $table->json("criterio")->nullable(); // {regionIds:[], userIds:[]}
            $table->enum("estado", ["borrador", "enviado", "archivado"])->default("borrador");
            $table->timestamp("enviado_at")->nullable();
            $table->timestamps();
            // Adjuntos: via morphs("documentable") de f1doc_documentos.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("f3cmu_comunicados");
    }
};