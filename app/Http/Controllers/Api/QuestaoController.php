<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Questao;
use Illuminate\Http\Request;

class QuestaoController extends Controller
{
    public function index(Request $request)
    {
        $questoes = Questao::with(['materia', 'assunto', 'alternativas'])
            ->where('ativa', true)
            ->when($request->ano, function ($query, $ano) {
                $query->where('ano', $ano);
            })
            ->when($request->materia_id, function ($query, $materiaId) {
                $query->where('materia_id', $materiaId);
            })
            ->when($request->assunto_id, function ($query, $assuntoId) {
                $query->where('assunto_id', $assuntoId);
            })
            ->orderBy('ano', 'desc')
            ->orderBy('numero')
            ->paginate(20);

        return response()->json([
            'sucesso' => true,
            'dados' => $questoes
        ]);
    }
}