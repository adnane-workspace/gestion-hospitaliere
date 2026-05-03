<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('admin.dashboard');
        }

        if ($user->isMedecin()) {
            $medecin = $user->medecin;

            if (!$medecin) {
                return "Profil médecin non trouvé pour cet utilisateur.";
            }

            $stats = [
                'rdv_today' => RendezVous::where('medecin_id', $medecin->id)
                    ->whereDate('date_heure_debut', Carbon::today())
                    ->count(),
                'consultations_done' => Consultation::where('medecin_id', $medecin->id)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count(),
                'new_patients' => Patient::whereMonth('created_at', Carbon::now()->month)->count(),
            ];

            $appointments = RendezVous::with('patient.user')
                ->where('medecin_id', $medecin->id)
                ->whereDate('date_heure_debut', Carbon::today())
                ->orderBy('date_heure_debut', 'asc')
                ->get();

            return view('medecin.dashboard', compact('stats', 'appointments'));
        }

        if ($user->isPatient()) {
            return view('patient.dashboard');
        }

        return redirect('/');
    }
}
