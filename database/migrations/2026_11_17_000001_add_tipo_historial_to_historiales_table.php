<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('historiales', function (Blueprint $table): void {
            $table->string('tipo_historial', 20)->default('completo');
        });
    }

    public function down(): void
    {
        Schema::table('historiales', function (Blueprint $table): void {
            $table->dropColumn('tipo_historial');
        });
    }
};
