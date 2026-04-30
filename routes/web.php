<?php

use App\Http\Controllers\ProfileController;
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

    // Redirection intelligente après login (Dashboard par défaut)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isMedecin()) return redirect()->route('medecin.dashboard');
        if ($user->isPatient()) return redirect()->route('patient.dashboard');
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    // --- ESPACE ADMIN ---
    Route::middleware('auth.role:admin')->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    // --- ESPACE MEDECIN ---
    Route::middleware('auth.role:medecin')->group(function () {
        Route::get('/medecin/dashboard', function () {
            return view('medecin.dashboard');
        })->name('medecin.dashboard');
        
        // Modules consultations, ordonnances, etc.
    });

    // --- ESPACE PATIENT ---
    Route::middleware('auth.role:patient')->group(function () {
        Route::get('/patient/dashboard', function () {
            return view('patient.dashboard');
        })->name('patient.dashboard');
        
        // Modules prise de RDV, mon dossier, etc.
    });

    // --- GESTION PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
