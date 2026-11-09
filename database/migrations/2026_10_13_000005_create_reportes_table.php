<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('reportes', function (Blueprint $table) {
        $table->id(); $table->foreignId('historial_id')->constrained('historiales')->cascadeOnDelete();
        $table->longText('contenido'); $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists('reportes'); }
};
