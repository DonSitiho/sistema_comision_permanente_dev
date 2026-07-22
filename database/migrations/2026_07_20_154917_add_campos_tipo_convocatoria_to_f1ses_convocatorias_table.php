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
        Schema::table('f1ses_convocatorias', function (Blueprint $table) {
            $table->enum("tipo_conv", ["ordinaria", "extra ordinaria", "regional","multi region","municipal"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('f1ses_convocatorias', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_conv',
            ]);
        });
    }
};
