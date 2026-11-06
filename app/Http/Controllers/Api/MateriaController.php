<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materia;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('assuntos')->get();

        return response()->json([
            'sucesso' => true,
            'dados' => $materias
        ]);
    }
}