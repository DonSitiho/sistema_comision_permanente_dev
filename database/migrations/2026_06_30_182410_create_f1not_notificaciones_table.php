<?php
// database/migrations/xxxx_create_f1not_notificaciones_table.php

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
        Schema::create('f1not_notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->enum("tipo", ["convocatoria", "acuerdo","compromiso", "mensaje", "sistema"]);
            $table->string("titulo", 200);
            $table->text("mensaje");
            $table->string("url_destino", 255)->nullable();
            $table->timestamp("leida_at")->nullable();
            $table->timestamps();
            $table->index(["user_id","leida_at"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1not_notificaciones');
    }
};
