<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Questao;
use App\Models\Alternativa;

class QuestaoSeeder extends Seeder
{
    public function run(): void
    {
        $questoes = [
            [
                'numero' => 1,
                'enunciado' => 'Ao abordar um aspecto da celebração do Halloween, esse texto tem por objetivo:',
                'alternativas' => [
                    'A' => 'Denunciar a violência contra crianças indígenas.',
                    'B' => 'Descrever costumes tradicionais em celebrações indígenas.',
                    'C' => 'Valorizar as vestimentas características dos povos originários.',
                    'D' => 'Criticar a exploração indevida de elementos da identidade indígena.',
                    'E' => 'Sugerir ações de combate ao preconceito contra os povos originários.',
                ],
                'correta' => 'D',
            ],
            [
                'numero' => 2,
                'enunciado' => 'Nesse texto, ao discorrer sobre a relevância da filosofia, a escritora Angela Davis tem por objetivo:',
                'alternativas' => [
                    'A' => 'Criticá-la pela restrição temática.',
                    'B' => 'Vinculá-la ao universo acadêmico.',
                    'C' => 'Afastá-la da abordagem socrática.',
                    'D' => 'Aproximá-la dos problemas sociais.',
                    'E' => 'Responsabilizá-la pela pobreza humana.',
                ],
                'correta' => 'D',
            ],
            [
                'numero' => 3,
                'enunciado' => 'Nesse poema, de uma autora de ascendência indígena, o eu lírico ressalta a:',
                'alternativas' => [
                    'A' => 'Potência dos astros celestes.',
                    'B' => 'Origem das plantas e dos animais.',
                    'C' => 'Importância do apego à terra natal.',
                    'D' => 'Relação entre seres humanos e natureza.',
                    'E' => 'Conexão entre o tempo real e o tempo imaginário.',
                ],
                'correta' => 'D',
            ],
            [
                'numero' => 4,
                'enunciado' => 'Nesse texto, a expressão "snowflake generation" é usada para:',
                'alternativas' => [
                    'A' => 'Abordar obstáculos impostos a universitários.',
                    'B' => 'Destacar mensagens de incentivo a estudantes.',
                    'C' => 'Estimular ações proativas em situações de emergência.',
                    'D' => 'Retratar relações conflituosas em ambiente universitário.',
                    'E' => 'Apontar posturas de uma juventude avessa a contrariedades.',
                ],
                'correta' => 'E',
            ],
        ];

        foreach ($questoes as $dados) {
            $questao = Questao::updateOrCreate(
                [
                    'ano' => 2025,
                    'numero' => $dados['numero'],
                    'caderno' => 'Caderno 1 - Azul',
                ],
                [
                    'materia_id' => 1,
                    'assunto_id' => 7,
                    'enunciado' => $dados['enunciado'],
                    'dificuldade' => 'media',
                    'ativa' => true,
                ]
            );

            foreach ($dados['alternativas'] as $letra => $texto) {
                Alternativa::updateOrCreate(
                    [
                        'questao_id' => $questao->id,
                        'letra' => $letra,
                    ],
                    [
                        'texto' => $texto,
                        'correta' => $letra === $dados['correta'],
                    ]
                );
            }
        }

        echo "4 questões cadastradas com sucesso!\n";
    }
}