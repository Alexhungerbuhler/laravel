<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\CharacterController;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    // Routes d’authentification Breeze
    require __DIR__ . '/auth.php';
    
    // Point d’entrée Vue pour la page d’accueil (Home.vue)
    Route::view('/', 'app')->name('home');
    
    Route::middleware('auth')->group(function () {
        Route::view('/character/create', 'app')->name('character.create');
        Route::view('/dashboard', 'dashboard')->name('dashboard');
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

    

    