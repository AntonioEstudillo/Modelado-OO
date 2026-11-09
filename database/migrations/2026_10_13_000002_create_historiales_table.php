<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('historiales', function (Blueprint $table) {
        $table->id(); $table->foreignId('paciente_id')->unique()->constrained('pacientes')->cascadeOnDelete();
        $table->string('tipo', 20); $table->text('diagnostico')->nullable();
        $table->json('antecedentes')->nullable(); $table->json('medicamentos')->nullable(); $table->json('alergias')->nullable(); $table->json('estudios')->nullable(); $table->timestamps();
    }); }
    public function down(): void { Schema::dropIfExists('historiales'); }
};
