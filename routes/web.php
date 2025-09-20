<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route de login pour éviter l'erreur de redirection
Route::get('/login', function () {
    return response()->json(['message' => 'Veuillez vous connecter via l\'API'], 401);
})->name('login');
