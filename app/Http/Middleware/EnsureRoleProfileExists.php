<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleProfileExists
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Utilisateur non authentifie.');
        }

        if ($role === 'patient' && $user->isPatient() && !$user->patient) {
            return redirect()->route('dashboard')->with('error', 'Profil patient incomplet. Contactez l\'administration.');
        }

        if ($role === 'medecin' && $user->isMedecin() && !$user->medecin) {
            return redirect()->route('dashboard')->with('error', 'Profil medecin incomplet. Contactez l\'administration.');
        }

        return $next($request);
    }
}
