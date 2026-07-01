<?php
// database/migrations/xxxx_add_email_notificaciones_to_users.php
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
        Schema::table('users', function (Blueprint $table) {
            if(!Schema::hasColumn("users", "email_notificaciones")) {
                $table->string("email_notificaciones", 255)
                ->nullable()
                ->after("cargo");
            }
            if(!Schema::hasColumn("users", "email_notif_verificado_at")){
                $table->timestamp("email_notif_verificado_at")
                ->nullable()
                ->after("email_notificaciones");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(["email_notificacione","email_notif_verificado_at"]);
        });
    }
};
