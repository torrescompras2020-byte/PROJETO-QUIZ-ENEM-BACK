<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questao extends Model
{
    protected $table = 'questoes';

    protected $fillable = [
        'materia_id',
        'assunto_id',
        'ano',
        'numero',
        'idioma',
        'caderno',
        'enunciado',
        'texto_apoio',
        'imagem_url',
        'dificuldade',
        'ativa',
    ];

    protected function casts(): array
    {
        return [
            'ativa' => 'boolean',
        ];
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function assunto(): BelongsTo
    {
        return $this->belongsTo(Assunto::class);
    }

    public function alternativas(): HasMany
    {
        return $this->hasMany(Alternativa::class);
    }
}