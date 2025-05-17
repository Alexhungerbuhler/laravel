<?php 

use App\Http\Controllers\CharacterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;



Route::middleware('auth')->group(function () {
    Route::get   ('/character', [CharacterController::class, 'show']);
    Route::post  ('/character', [CharacterController::class, 'store']);
    Route::delete('/character', [CharacterController::class, 'destroy']);
    Route::get('/map',      [GameController::class,'getMap']);
    Route::post('/move',    [GameController::class,'move']);
    Route::post('/combat',  [GameController::class,'combat']);
    Route::post('/loot',    [GameController::class,'loot']);
    Route::post('/use-item',[GameController::class,'useItem']);
    Route::post('/flee',    [GameController::class,'flee']);
    Route::post('/restart', [GameController::class,'restart']);
});

use Illuminate\Http\Request;


// Récupérer l'utilisateur authentifié
Route::middleware('auth')->get('/user', function(Request $req){
    return response()->json($req->user());
});

Route::middleware('auth')->get('/character', function (Request $req) {
    // Récupère le personnage lié à l’utilisateur
    return $req->user()->character; 
});

