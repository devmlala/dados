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

//artigos lattes
Route::prefix('lattes')->group(function () {
    Route::get('/', [LattesController::class, 'index'])->name('lattes.index');

    Route::prefix('docentes')->group(function () {
        Route::get('artigos', [LattesController::class, 'artigos'])->name('lattes.docentes.artigos');
        // Futuras rotas:
        // Route::get('resumos', [LattesController::class, 'resumos'])->name('lattes.docentes.resumos');
    });
});
