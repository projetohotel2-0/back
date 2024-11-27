<?php

use App\Http\Controllers\Api\LancheController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//======================Rotas usuários=======================
//Rota para listar todos os usuários
Route::get('users', [PostController::class, "index"]);

//Rota para criar um usuário
Route::post('users', [PostController::class, "store"]);

//======================Rotas Lanches=======================
//Rota para listar um lanche em específico
Route::get('lanches/{id}', [LancheController::class, 'show']);

//Rota para listar todos os lanches
Route::get('lanches', [LancheController::class, "index"]);

//Rota para criar um lanche
Route::post('lanches', [LancheController::class, "store"]);

//Rota para excluir um lanche (pode ser recuperado posteriormente)
Route::delete('/lanches/{id}', [LancheController::class, "destroy"]);

//Rota para consultar um lanche excluido
Route::get('lanchesDestroy', [LancheController::class, "consultDestroy"]);

//Rota para restaurar um lanche
// Route::get('lancheRestaure', [LancheController::class, "consultRestore"]);
Route::patch('lancheRestore/{id}', [LancheController::class, 'consultRestore']);

//======================Rotas Serviços===================
//======================Rotas passeios===================
