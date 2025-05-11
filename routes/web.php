<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

//  Page d'accueil (Vue SPA injectée dans app.blade.php)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('app');
})->name('home');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

//  Auth Breeze (login/register/profile)
require __DIR__.'/auth.php';



//  Routes protégées (si connecté)
Route::middleware('auth')->group(function () {

    //  Création personnage uniquement si non existant
    Route::get('/character/create', function (Request $req) {
        if ($req->user()->character) {
            return redirect()->route('dashboard');
        }
        return view('app');
    })->name('character.create');


    //  Dashboard uniquement si personnage existant
    Route::get('/dashboard', function (Request $req) {
        if (! $req->user()->character) {
            return redirect()->route('character.create');
        }
        return view('app');
    })->name('dashboard');

    Route::post('/character', [CharacterController::class, 'store']);
    Route::delete('/character', [CharacterController::class, 'destroy']);
});

// Toutes les autres routes Vue (SPA)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->middleware(['web']);
