<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ACCÈS PUBLIC ---
Route::get('/', function () {
    return redirect()->route('login');
});

// --- ROUTES AUTHENTIFIÉES ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Point d'entrée unique qui utilise la logique du DashboardController
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- ESPACE ADMIN ---
    Route::middleware('auth.role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });

    // --- ESPACE MEDECIN ---
    Route::middleware('auth.role:medecin')->group(function () {
        Route::get('/medecin/dashboard', [DashboardController::class, 'index'])->name('medecin.dashboard');
        // Autres routes médecin ici...
    });

    // --- ESPACE PATIENT ---
    Route::middleware('auth.role:patient')->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'index'])->name('patient.dashboard');
        // Autres routes patient ici...
    });

    // --- GESTION PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
