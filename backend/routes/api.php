<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\DemandeApiController;
use App\Http\Controllers\Api\LivraisonController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/catalog', [CatalogController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

// Demandes livreur / partenaire (public, limité à 5 envois par minute)
Route::post('/applications', [DemandeApiController::class, 'store'])->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Application chauffeur
Route::middleware(['auth:sanctum', 'role:chauffeur'])->group(function () {
    Route::get('/mes-livraisons', [LivraisonController::class, 'index']);
    Route::get('/livraisons/{commande}', [LivraisonController::class, 'show']);
    Route::patch('/livraisons/{commande}/statut', [LivraisonController::class, 'updateStatut']);
    Route::post('/livraisons/{commande}/note', [LivraisonController::class, 'addNote']);
});

// Module client
Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
    Route::get('/client/commandes', [ClientApiController::class, 'index']);
    Route::post('/orders', [ClientApiController::class, 'store']);
});