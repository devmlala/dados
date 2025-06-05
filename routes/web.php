<?php

use Illuminate\Support\Facades\Route;


# Index
use App\Http\Controllers\IndexController;
Route::get('/', [IndexController::class, 'index']);

# Restrito
use App\Http\Controllers\RestritoController;
Route::get('/restrito', [RestritoController::class, 'restrito']);

# Colegiados
use App\Http\Controllers\Publico\ColegiadoController;
Route::get('/colegiados', [ColegiadoController::class, 'index']);
Route::get('/colegiados/{codclg}/{sglclg}', [ColegiadoController::class, 'show']);

# Restrito: Estagiários
use App\Http\Controllers\Restrito\EstagiarioController;
Route::get('/restrito/estagiarios', [EstagiarioController::class, 'index']);


# Restrito...
use App\Http\Controllers\Restrito\IntercambistasController;
Route::get('/restrito/intercambitas/recebidos', [IntercambistasController::class, 'listarIntercambistasRecebidos']);




use App\Http\Controllers\LattesController;
Route::get('/lattes', [LattesController::class, 'index'])->name('lattes.index');
Route::get('/lattes/{codpes}', [LattesController::class, 'show'])->name('lattes.show');
Route::get('/lattes/download/{codpes}/{secao}/{subsecao}', [LattesController::class, 'baixarExcel'])->name('lattes.download');
