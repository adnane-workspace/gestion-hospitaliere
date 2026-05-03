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

            $recent_consultations = Consultation::with('patient.user')
                ->where('medecin_id', $medecin->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('medecin.dashboard', compact('stats', 'appointments', 'recent_consultations'));
        }

        if ($user->isPatient()) {
            $patient = $user->patient;

            if (!$patient) {
                return "Profil patient non trouvé pour cet utilisateur.";
            }

            $nextAppointment = RendezVous::with('medecin.user')
                ->where('patient_id', $patient->id)
                ->where('date_heure_debut', '>=', Carbon::now())
                ->orderBy('date_heure_debut', 'asc')
                ->first();

            $history = Consultation::with('medecin.user', 'service')
                ->where('patient_id', $patient->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('patient.dashboard', compact('patient', 'nextAppointment', 'history'));
        }

        return redirect('/');
    }
}
