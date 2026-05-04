<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\MedecinDisponibiliteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
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
        Route::get('/admin/comptabilite', [DashboardController::class, 'comptabilite'])->name('admin.comptabilite');
        Route::get('/admin/medecins', [App\Http\Controllers\AdminController::class, 'indexMedecins'])->name('admin.medecins.index');
        Route::get('/admin/medecins/en-attente', [App\Http\Controllers\AdminController::class, 'pendingMedecins'])->name('admin.medecins.pending');
        Route::post('/admin/medecins/{user}/activer', [App\Http\Controllers\AdminController::class, 'activateMedecin'])->name('admin.medecins.activate');
    });

    // --- ESPACE MEDECIN ---
    Route::middleware(['auth.role:medecin', 'ensure.profile:medecin'])->group(function () {
        Route::get('/medecin/dashboard', [DashboardController::class, 'index'])->name('medecin.dashboard');
        Route::get('/medecin/rapport', [DashboardController::class, 'report'])->name('medecin.report');
        Route::get('/medecin/consultations', [ConsultationController::class, 'index'])->name('medecin.consultations');
        Route::get('/medecin/consultations/{consultation}', [ConsultationController::class, 'show'])->name('medecin.consultations.show');
        Route::post('/medecin/rendezvous/{rendezvous}/demarrer-consultation', [ConsultationController::class, 'startFromRendezVous'])->name('medecin.consultations.start');
        Route::post('/medecin/rendezvous/{rendezvous}/confirmer', [App\Http\Controllers\RendezVousController::class, 'confirm'])->name('medecin.rendezvous.confirm');
        Route::get('/medecin/rendezvous', [App\Http\Controllers\RendezVousController::class, 'medecinIndex'])->name('medecin.rendezvous.index');
        Route::get('/medecin/disponibilites', [MedecinDisponibiliteController::class, 'index'])->name('medecin.disponibilites.index');
        Route::post('/medecin/disponibilites', [MedecinDisponibiliteController::class, 'store'])->name('medecin.disponibilites.store');
        // Autres routes médecin ici...
    });

    // --- ESPACE PATIENT ---
    Route::middleware(['auth.role:patient', 'ensure.profile:patient'])->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'index'])->name('patient.dashboard');
        Route::get('/patient/profile', [PatientController::class, 'myProfile'])->name('patient.profile');
        Route::get('/patient/rendezvous', [App\Http\Controllers\RendezVousController::class, 'index'])->name('patient.rendezvous.index');
        Route::get('/patient/rendezvous/nouveau', [App\Http\Controllers\RendezVousController::class, 'create'])->name('patient.rendezvous.create');
        Route::post('/patient/rendezvous', [App\Http\Controllers\RendezVousController::class, 'store'])->name('patient.rendezvous.store');
    });

    Route::middleware('auth.role:admin,medecin')->group(function () {
        // --- GESTION PATIENTS ---
        Route::resource('patients', PatientController::class);
    });

    Route::middleware(['auth.role:patient', 'ensure.profile:patient'])->group(function () {
        Route::get('/patient/historique/pdf', [ConsultationController::class, 'exportPatientHistoryPdf'])->name('patient.history.pdf');
    });

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // --- GESTION PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
