<?php
// database/migrations/xxxx_xx_xx_adapt_users_for_scpc.php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
 
            // Eliminar columna suelta id_area (no tenía FK real)
            if (Schema::hasColumn('users', 'id_area')) {
                $table->dropColumn('id_area');
            }
 
            // Relación real con dependencias
            if (!Schema::hasColumn('users', 'dependencia_id')) {
                $table->foreignId('dependencia_id')
                      ->nullable()
                      ->after('is_active')
                      ->constrained('dependencias')
                      ->nullOnDelete();
            }
 
            // Cargo institucional del servidor público
            if (!Schema::hasColumn('users', 'cargo')) {
                $table->string('cargo', 150)
                      ->nullable()
                      ->after('dependencia_id');
            }
 
            // Columnas 2FA — preparadas, el flujo se activa en fase posterior.
            // Las dejamos ahora para no hacer otra migración alter luego.
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')
                      ->nullable()
                      ->after('cargo');
            }
            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')
                      ->nullable()
                      ->after('two_factor_secret');
            }
            if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')
                      ->nullable()
                      ->after('two_factor_recovery_codes');
            }
        });
    }
 
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dependencia_id']);
            $table->dropColumn([
                'dependencia_id', 'cargo',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
            ]);
            // Restaurar id_area suelta si se hace rollback
            $table->unsignedBigInteger('id_area')->nullable();
        });
    }
};
