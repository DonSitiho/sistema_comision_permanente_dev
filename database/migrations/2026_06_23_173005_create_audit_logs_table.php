<?php
// database/migrations/xxxx_xx_xx_create_audit_logs_table.php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
 
            // Nullable: permite registrar intentos fallidos sin usuario autenticado
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
 
            // Qué ocurrió: login, logout, login_failed, created, updated, deleted...
            $table->string('accion', 100);
 
            // Sobre qué entidad (users, dependencias, acuerdos, etc.)
            $table->string('entidad', 100)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
 
            // Snapshot del cambio: {"before": {...}, "after": {...}}
            $table->json('valores')->nullable();
 
            // Contexto de la petición
            $table->string('ip', 45)->nullable();         // IPv4 e IPv6
            $table->string('user_agent', 255)->nullable();
 
            // Solo created_at — este registro nunca se actualiza
            $table->timestamp('created_at')->useCurrent();
 
            // Índices para las consultas más comunes de la bitácora
            $table->index('user_id');
            $table->index(['entidad', 'entidad_id']);
            $table->index('accion');
            $table->index('created_at');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
