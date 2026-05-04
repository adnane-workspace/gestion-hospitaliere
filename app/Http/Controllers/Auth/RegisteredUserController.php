<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:medecin,patient'],
            // Champs patient requis si le rôle est patient
            'prenom' => ['required_if:role,patient', 'nullable', 'string', 'max:100'],
            'telephone' => ['required_if:role,patient', 'nullable', 'string', 'max:20'],
            'genre' => ['required_if:role,patient', 'nullable', 'in:homme,femme,autre'],
            'date_naissance' => ['required_if:role,patient', 'nullable', 'date'],
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => $request->role === 'medecin' ? false : true,
            ]);

            if ($user->role === 'patient') {
                Patient::create([
                    'user_id' => $user->id,
                    'nom' => $request->name, // Le 'name' de l'user sert de nom de famille
                    'prenom' => $request->prenom ?? '',
                    'email' => $request->email,
                    'telephone' => $request->telephone ?? '',
                    'genre' => $request->genre ?? 'homme',
                    'date_naissance' => $request->date_naissance ?? now(),
                    'numero_dossier' => 'PAT-' . strtoupper(bin2hex(random_bytes(4))),
                    'statut' => 'actif',
                ]);
            }

            event(new Registered($user));

            if (!$user->is_active) {
                return redirect()->route('login')->with('status', 'Votre compte médecin a été créé et est en attente d\'approbation par l\'administrateur.');
            }

            Auth::login($user);

            if ($user->role === 'patient') {
                return redirect(route('patient.dashboard', absolute: false));
            }

            return redirect(route('dashboard', absolute: false));
        });
    }
}
