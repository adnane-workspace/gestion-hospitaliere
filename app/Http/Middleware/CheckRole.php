<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  Le rôle requis pour accéder à la route
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            // Optionnel : Vous pouvez rediriger vers le dashboard spécifique au rôle réel
            // ou renvoyer une erreur 403.
            abort(403, "Accès non autorisé : Vous n'avez pas le rôle $role.");
        }

        return $next($request);
    }
}
