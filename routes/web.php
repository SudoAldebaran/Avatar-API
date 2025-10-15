<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BibliothequeController;
use App\Http\Controllers\AuthController;

// ROUTE ACCUEIL
Route::get('/', [HomeController::class, 'index'])->name('home');

// ROUTE POUR LA PAGE BIBLIOTHÈQUE (PROTÉGÉE PAR AUTH)
// Route::get('/bibliotheque', [BibliothequeController::class, 'index'])->middleware('auth')->name('bibliotheque');

// ROUTE POUR LA PAGE DE CONNEXION
Route::get('/login', function () {
    return view('login');
})->name('login');

// ROUTE POUR LA PAGE D'INSCRIPTION
Route::get('/register', function () {
    return view('register');
})->name('register');

// ROUTE POUR LA PAGE ADMIN DE GESTION DES CLÉS API (VUE UNIQUEMENT)
Route::get('/admin/api-keys', function () {
    return view('api-keys');
})->name('api-keys.index');


Route::get('/bibliotheque', [BibliothequeController::class, 'index']);
