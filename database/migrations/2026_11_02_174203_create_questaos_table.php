<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materia_id')
                ->constrained('materias')
                ->cascadeOnDelete();

            $table->foreignId('assunto_id')
                ->nullable()
                ->constrained('assuntos')
                ->nullOnDelete();

            $table->unsignedSmallInteger('ano');
            $table->unsignedSmallInteger('numero')->nullable();
            $table->string('caderno', 100)->nullable();

            $table->longText('enunciado');
            $table->longText('texto_apoio')->nullable();
            $table->text('imagem_url')->nullable();

            $table->string('dificuldade', 20)->default('media');
            $table->boolean('ativa')->default(true);

            $table->timestamps();

            $table->index(['ano', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questoes');
    }
};