<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;
use App\Models\Assunto;
use Illuminate\Support\Str;

class AssuntoSeeder extends Seeder
{
    public function run(): void
    {
        $assuntos = [
            'linguagens' => [
                'Interpretação de Texto',
                'Gêneros Textuais',
                'Gramática',
                'Literatura',
                'Figuras de Linguagem',
                'Variação Linguística',
                'Língua Estrangeira',
            ],

            'ciencias-humanas' => [
                'História do Brasil',
                'História Geral',
                'Geografia',
                'Filosofia',
                'Sociologia',
                'Geopolítica',
            ],

            'ciencias-da-natureza' => [
                'Biologia',
                'Ecologia',
                'Genética',
                'Física',
                'Química',
                'Meio Ambiente',
            ],

            'matematica' => [
                'Matemática Básica',
                'Geometria',
                'Funções',
                'Estatística',
                'Probabilidade',
                'Porcentagem',
                'Análise Combinatória',
            ],
        ];

        foreach ($assuntos as $slugMateria => $listaAssuntos) {
            $materia = Materia::where('slug', $slugMateria)->first();
            if (!$materia) {
    continue;
}

            foreach ($listaAssuntos as $nome) {
                Assunto::firstOrCreate(
                    [
                        'materia_id' => $materia->id,
                        'slug' => Str::slug($nome),
                    ],
                    [
                        'nome' => $nome,
                    ]
                );
            }
        }
    }
}