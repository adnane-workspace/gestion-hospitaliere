<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedecinDisponibiliteRequest;
use Illuminate\Http\Request;

class MedecinDisponibiliteController extends Controller
{
    public function index(Request $request)
    {
        $medecin = $request->user()->medecin;

        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil medecin non trouve.');
        }

        $disponibilites = $medecin->disponibilites()->orderBy('jour_semaine')->orderBy('heure_debut')->get();

        return view('medecin.disponibilites.index', compact('disponibilites'));
    }

    public function store(StoreMedecinDisponibiliteRequest $request)
    {
        $medecin = $request->user()->medecin;
        if (!$medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil medecin non trouve.');
        }

        $medecin->disponibilites()->create($request->validated());

        return back()->with('success', 'Disponibilite ajoutee.');
    }
}
