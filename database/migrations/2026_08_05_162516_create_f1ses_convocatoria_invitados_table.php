<?php

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
        Schema::create('f1ses_convocatoria_invitados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convocatoria_id')->constrained('f1ses_convocatorias')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('rol_invitado', 50)->nullable();
            $table->timestamp('confirmado_at')->nullable();
            $table->timestamps();
            $table->unique(['convocatoria_id', 'user_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f1ses_convocatoria_invitados');
    }
};
