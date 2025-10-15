<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SvgElementController;
use App\Http\Controllers\AvatarCompletController;
use App\Http\Controllers\BibliothequeController;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Controllers\AvatarApiController;

// ROUTE POUR RÉCUPERER TOUS LES ÉLÉMENTS SVG DISPONIBLES
Route::get('/svg-elements', [SvgElementController::class, 'index']);

// ROUTE PUBLIQUE POUR RÉCUPÉRER LES AVATARS (NOM ET SVG) AVEC CLEF API
Route::middleware([ApiKeyMiddleware::class])->get('/public-avatars', [AvatarApiController::class, 'allAvatarsMinimal']);

// ROUTE POUR RÉCUPÉRER L'UTILISATEUR AUTHENTIFIÉ
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ROUTES PROTÉGÉES POUR LA GESTION DES AVATARS DE L'UTILISATEUR
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/avatar_complet', [AvatarCompletController::class, 'store']);
    Route::get('/bibliotheque', [BibliothequeController::class, 'recuperer']);
    Route::delete('/avatars/{id}', [BibliothequeController::class, 'delete']);
});

// ROUTES POUR L'AUTHENTIFICATION (INSCRIPTION, CONNEXION, DECONNEXION)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ROUTES ADMIN POUR LA GESTION DES CLÉS API
Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
    Route::get('/admin/api-keys', [ApiKeyController::class, 'index']);
    Route::post('/admin/api-keys/generate', [ApiKeyController::class, 'generate']);
    Route::patch('/admin/api-keys/{id}/toggle', [ApiKeyController::class, 'toggleStatus']);
    Route::delete('/admin/api-keys/{id}', [ApiKeyController::class, 'delete']);
});
