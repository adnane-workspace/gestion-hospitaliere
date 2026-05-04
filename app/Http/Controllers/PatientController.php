<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientDirectoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function __construct(
        private readonly PatientDirectoryService $patientDirectoryService
    ) {
    }

    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);
        $patients = $this->patientDirectoryService->paginate($request, 15);

        return view('patients.index', compact('patients'));
    }

    /**
     * Display the authenticated patient's profile.
     */
    public function myProfile()
    {
        $patient = Auth::user()->patient;
        
        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient non trouvé.');
        }

        return $this->show($patient);
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);
        $patient->load(['user', 'medecinTraitant', 'consultations.medecin', 'rendezvous.medecin']);
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $this->authorize('create', Patient::class);
        $medecins = \App\Models\Medecin::with('user')->get();
        $services = \App\Models\Service::all();
        return view('patients.create', compact('medecins', 'services'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(StorePatientRequest $request)
    {
        $this->authorize('create', Patient::class);
        $patient = Patient::create($request->validated());

        return redirect()->route('patients.show', $patient)->with('success', 'Patient créé avec succès.');
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        $this->authorize('update', $patient);
        $medecins = \App\Models\Medecin::with('user')->get();
        $services = \App\Models\Service::all();
        return view('patients.edit', compact('patient', 'medecins', 'services'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $this->authorize('update', $patient);
        $patient->update($request->validated());

        return redirect()->route('patients.show', $patient)->with('success', 'Dossier patient mis à jour.');
    }
}
