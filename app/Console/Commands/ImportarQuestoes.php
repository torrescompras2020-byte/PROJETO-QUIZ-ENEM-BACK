<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportarQuestoes extends Command
{
    protected $signature = 'enem:importar';

    protected $description = 'Importa questões do ENEM com gabarito oficial';

    public function handle()
    {
        $arquivo = database_path('data/questoes_enem_2025_final.json');

        if (!File::exists($arquivo)) {
            $this->error('Arquivo questoes_enem_2025_final.json não encontrado.');
            return self::FAILURE;
        }

        $questoes = json_decode(
            File::get($arquivo),
            true
        );

        if (!is_array($questoes)) {
            $this->error('Não foi possível ler o arquivo JSON.');
            return self::FAILURE;
        }

        $questoesPreparadas = [];

        foreach ($questoes as $questao) {

            $numero = (int) ($questao['numero'] ?? 0);
            $idioma = $questao['idioma'] ?? '';
            $texto = trim($questao['texto'] ?? '');
            $alternativas = $questao['alternativas'] ?? [];
            $respostaCorreta = strtoupper(
                $questao['resposta_correta'] ?? ''
            );

            if ($numero < 1 || $numero > 90) {
                $this->error("Número de questão inválido: {$numero}");
                return self::FAILURE;
            }

            if (!in_array($idioma, ['ingles', 'espanhol', 'portugues'])) {
                $this->error("Idioma inválido na questão {$numero}.");
                return self::FAILURE;
            }

            if ($texto === '') {
                $this->error("Texto vazio na questão {$numero}.");
                return self::FAILURE;
            }

            if (!is_array($alternativas) || count($alternativas) !== 5) {
                $this->error("A questão {$numero} não possui 5 alternativas.");
                return self::FAILURE;
            }

            if (!in_array($respostaCorreta, ['A', 'B', 'C', 'D', 'E'])) {
                $this->error("Gabarito inválido na questão {$numero}.");
                return self::FAILURE;
            }

            foreach (['A', 'B', 'C', 'D', 'E'] as $letra) {
                if (empty($alternativas[$letra])) {
                    $this->error("Alternativa {$letra} vazia na questão {$numero}.");
                    return self::FAILURE;
                }
            }

            /*
             * Localiza o início da alternativa A.
             * Aceita tabulação ou dois ou mais espaços.
             * Isso evita confundir "A autora..." com a alternativa A.
             */
            if (!preg_match(
                '/(?:^|\R)A(?:\t+| {2,})/u',
                $texto,
                $encontrada,
                PREG_OFFSET_CAPTURE
            )) {
                $this->error("Não foi possível separar o enunciado da questão {$numero}.");
                return self::FAILURE;
            }

            $posicaoAlternativaA = $encontrada[0][1];

            $enunciado = trim(
                substr($texto, 0, $posicaoAlternativaA)
            );

            if ($enunciado === '') {
                $this->error("Enunciado vazio na questão {$numero}.");
                return self::FAILURE;
            }

            // Questões 1 a 45: Linguagens.
            // Questões 46 a 90: Ciências Humanas.
            $materiaId = $numero <= 45 ? 1 : 2;

            $questoesPreparadas[] = [
                'ano' => (int) ($questao['ano'] ?? 2025),
                'caderno' => $questao['caderno'] ?? 'Caderno 1 - Azul',
                'numero' => $numero,
                'idioma' => $idioma,
                'materia_id' => $materiaId,
                'assunto_id' => null,
                'enunciado' => $enunciado,
                'texto_apoio' => null,
                'imagem_url' => null,
                'dificuldade' => 'media',
                'ativa' => true,
                'alternativas' => $alternativas,
                'resposta_correta' => $respostaCorreta,
            ];
        }

        $this->info('Validação concluída. Iniciando importação...');

        try {
            DB::transaction(function () use ($questoesPreparadas) {

                foreach ($questoesPreparadas as $dados) {

                    $chave = [
                        'ano' => $dados['ano'],
                        'numero' => $dados['numero'],
                        'idioma' => $dados['idioma'],
                        'caderno' => $dados['caderno'],
                    ];

                    $valores = [
                        'materia_id' => $dados['materia_id'],
                        'assunto_id' => $dados['assunto_id'],
                        'enunciado' => $dados['enunciado'],
                        'texto_apoio' => $dados['texto_apoio'],
                        'imagem_url' => $dados['imagem_url'],
                        'dificuldade' => $dados['dificuldade'],
                        'ativa' => $dados['ativa'],
                        'updated_at' => now(),
                    ];

                    DB::table('questoes')->updateOrInsert(
                        $chave,
                        array_merge($valores, [
                            'created_at' => now(),
                        ])
                    );

                    $questaoId = DB::table('questoes')
                        ->where($chave)
                        ->value('id');

                    foreach ($dados['alternativas'] as $letra => $textoAlternativa) {

                        $letra = strtoupper($letra);

                        DB::table('alternativas')->updateOrInsert(
                            [
                                'questao_id' => $questaoId,
                                'letra' => $letra,
                            ],
                            [
                                'texto' => $textoAlternativa,
                                'correta' => $letra === $dados['resposta_correta'],
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    }
                }
            });

            $this->newLine();
            $this->info('IMPORTAÇÃO CONCLUÍDA COM SUCESSO!');
            $this->info('Questões importadas: ' . count($questoesPreparadas));
            $this->info('Alternativas importadas: ' . (count($questoesPreparadas) * 5));
            $this->info('Gabaritos associados: ' . count($questoesPreparadas));

            return self::SUCCESS;

        } catch (\Throwable $e) {

            $this->error('Erro durante a importação:');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
