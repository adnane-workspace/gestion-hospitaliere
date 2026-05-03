<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the doctor's consultations.
     */
    public function index()
    {
        $medecin = Auth::user()->medecin;

        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil médecin non trouvé.');
        }

        $consultations = Consultation::with('patient.user', 'service')
            ->where('medecin_id', $medecin->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('medecin.consultations.index', compact('consultations'));
    }
}
