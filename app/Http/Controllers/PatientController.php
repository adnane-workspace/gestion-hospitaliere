<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Patient::with(['user', 'medecinTraitant']);

        // Si c'est un médecin, on peut filtrer pour ne montrer que ses patients (optionnel)
        // Mais généralement, un médecin peut chercher n'importe quel patient dans l'hôpital.
        // Pour cet exemple, on montre tout mais on pourrait limiter si besoin.
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('numero_dossier', 'like', "%$search%")
                  ->orWhere('cin', 'like', "%$search%");
            });
        }

        $patients = $query->orderBy('nom', 'asc')->paginate(15);

        return view('patients.index', compact('patients'));
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        $patient->load(['user', 'medecinTraitant', 'consultations.medecin', 'rendezvous.medecin']);
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $medecins = \App\Models\Medecin::with('user')->get();
        $services = \App\Models\Service::all();
        return view('patients.create', compact('medecins', 'services'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'numero_dossier' => 'required|string|unique:patients,numero_dossier',
            'telephone' => 'required|string|max:20',
            'date_naissance' => 'required|date',
            'genre' => 'required|in:homme,femme,autre',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient)->with('success', 'Patient créé avec succès.');
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        $medecins = \App\Models\Medecin::with('user')->get();
        $services = \App\Models\Service::all();
        return view('patients.edit', compact('patient', 'medecins', 'services'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'statut' => 'required|in:actif,inactif,decede,transfere',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)->with('success', 'Dossier patient mis à jour.');
    }
}
