<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('tratamientos', function (Blueprint $table) {
        $table->id(); $table->foreignId('paciente_id')->constrained('pacientes')->cascadeOnDelete();
        $table->text('descripcion'); $table->string('estado')->default('activo'); $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists('tratamientos'); }
};
