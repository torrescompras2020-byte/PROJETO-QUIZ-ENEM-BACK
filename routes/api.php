<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MateriaController;
use App\Http\Controllers\Api\QuestaoController;

Route::get('/materias', [MateriaController::class, 'index']);
Route::get('/questoes', [QuestaoController::class, 'index']);