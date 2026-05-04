<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medecin;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Liste des médecins en attente d'activation.
     */
    public function pendingMedecins()
    {
        $pendingUsers = User::where('role', 'medecin')
            ->where('is_active', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.medecins.pending', compact('pendingUsers'));
    }

    /**
     * Activer un compte médecin.
     */
    public function activateMedecin(Request $request, User $user)
    {
        if ($user->role !== 'medecin') {
            return back()->with('error', 'Cet utilisateur n\'est pas un médecin.');
        }

        $user->update(['is_active' => true]);

        // Si le profil médecin n'existe pas encore, on le crée avec des valeurs par défaut
        if (!$user->medecin) {
            $service = Service::first();
            
            Medecin::create([
                'user_id' => $user->id,
                'nom' => $user->name,
                'prenom' => 'Dr.',
                'email' => $user->email,
                'telephone' => '0600000000', // Valeur par défaut requise par la BD
                'matricule' => 'MED-' . strtoupper(bin2hex(random_bytes(3))),
                'service_id' => $service ? $service->id : 1,
                'specialite' => 'Généraliste',
                'genre' => 'homme',
                'date_naissance' => now()->subYears(30),
                'date_embauche' => now(),
                'statut' => 'actif',
            ]);
        }

        return back()->with('success', "Le compte du médecin {$user->name} a été activé avec succès.");
    }
}
