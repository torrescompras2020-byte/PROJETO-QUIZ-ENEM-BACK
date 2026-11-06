<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materia_id')
                ->constrained('materias')
                ->cascadeOnDelete();

            $table->string('nome', 150);
            $table->string('slug', 180);
            $table->text('descricao')->nullable();
            $table->timestamps();

            $table->unique(['materia_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assuntos');
    }
};