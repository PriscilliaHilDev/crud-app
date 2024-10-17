<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié via le guard web
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Vérifier si l'utilisateur a le rôle requis
            if ($user->hasRole("admin")) {
                return $next($request); // L'utilisateur a le rôle requis, continuer
            } else {
                abort(404); // Si l'utilisateur n'a pas le rôle admin, erreur 404
            }
        } else {
            // Retourner la redirection si l'utilisateur n'est pas authentifié
            return redirect()->route('post.list');
        }

        // Par défaut, s'assurer que le middleware retourne une réponse
        return $next($request);
    }
}
