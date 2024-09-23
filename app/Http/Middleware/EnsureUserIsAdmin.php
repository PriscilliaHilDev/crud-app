<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Vérifie si l'utilisateur authentifié via le guard 'web' a le rôle 'admin'.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     * @return bool
     */
    private function checkIfUserIsAdmin($user)
    {
        return $user && $user->hasRole('admin'); // Vérifie si l'utilisateur a le rôle 'admin'
    }

    /**
     * Redirige l'utilisateur vers une page publique si non autorisé.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private function respondToUnauthorizedRequest($request)
    {
        return redirect()->route('post.list'); // Redirection vers la page publique de ton choix
    }

    /**
     * Gère la requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        // Vérifie si l'utilisateur est connecté via le guard 'web'
        if (backpack_auth()->user()) {
          
            // Si l'utilisateur n'est pas un admin, on le redirige
            if (! $this->checkIfUserIsAdmin(Auth::user())) {
                return $this->respondToUnauthorizedRequest($request);
            }else{
                return redirect()->guest(backpack_url('login'));
            }
        } else {
            // Si l'utilisateur n'est pas connecté, redirige également
            return $this->respondToUnauthorizedRequest($request);
        }

        return $next($request); // L'utilisateur a le rôle admin, on le laisse passer
    }
}
