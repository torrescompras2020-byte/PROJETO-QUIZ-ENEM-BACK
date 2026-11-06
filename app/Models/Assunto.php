<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assunto extends Model
{
    protected $table = 'assuntos';

    protected $fillable = [
        'materia_id',
        'nome',
        'slug',
        'descricao',
    ];

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }
}