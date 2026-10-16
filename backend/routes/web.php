<?php

use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', ClientController::class)->except('show');
    Route::resource('chauffeurs', ChauffeurController::class)->except('show');
    Route::resource('commandes', CommandeController::class);
    Route::post('commandes/{commande}/affecter', [CommandeController::class, 'affecter'])
        ->name('commandes.affecter');

    Route::get('demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
    Route::post('demandes/{demande}/accepter', [DemandeController::class, 'accepter'])->name('demandes.accepter');
    Route::post('demandes/{demande}/refuser', [DemandeController::class, 'refuser'])->name('demandes.refuser');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';