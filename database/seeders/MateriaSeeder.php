<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $materias = [
            ['nome' => 'Linguagens, Códigos e suas Tecnologias', 'slug' => 'linguagens'],
            ['nome' => 'Ciências Humanas e suas Tecnologias', 'slug' => 'ciencias-humanas'],
            ['nome' => 'Ciências da Natureza e suas Tecnologias', 'slug' => 'ciencias-da-natureza'],
            ['nome' => 'Matemática e suas Tecnologias', 'slug' => 'matematica'],
        ];

        foreach ($materias as $materia) {
            Materia::firstOrCreate(
                ['slug' => $materia['slug']],
                ['nome' => $materia['nome']]
            );
        }
    }
}