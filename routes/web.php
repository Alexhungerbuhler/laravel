<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\CharacterController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    Route::middleware('auth')->group(function () {
        // Formulaire de création
        Route::get('/character/create', [CharacterController::class,'create'])
             ->name('character.create');
        Route::post('/character',        [CharacterController::class,'store'])
             ->name('character.store');
    
        // Dashboard
        Route::get('/dashboard',         [CharacterController::class,'dashboard'])
             ->name('dashboard');
    
        // (eventuellement) suppression…
        Route::delete('/character',      [CharacterController::class,'destroy'])
             ->name('character.destroy');
    });

    // Page unique qui servira ton SPA Vue
Route::get('/game', function () {
    return view('app');
})->middleware('auth')->name('game');

require __DIR__.'/auth.php';
