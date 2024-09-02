<?php

namespace App\Http\Controllers\Auth;

use Log;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthProviderController extends Controller
{
    /**
     * Redirige l'utilisateur vers le fournisseur pour l'authentification.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        // Vérifie si le fournisseur est valide
        if (!in_array($provider, ['google', 'github'])) {
            abort(404, 'Provider not supported.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Gère la réponse du fournisseur après l'authentification.
     *
     * @param string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        // Vérifie si le fournisseur est valide
        if (!in_array($provider, ['google', 'github'])) {
            abort(404, 'Provider not supported.');
        }

        try {
            // Récupère l'utilisateur depuis le fournisseur
            $user = Socialite::driver($provider)->user();

            // Trouve ou crée un utilisateur dans la base de données
            $authUser = User::firstOrCreate(
                ['email' => $user->getEmail()],
                [
                    'name' => $user->getName(),
                    'password' => bcrypt(Str::random(60)) // Crée un mot de passe aléatoire pour l'utilisateur
                ]
            );

            // Connecte l'utilisateur
            Auth::login($authUser);

            // Redirige l'utilisateur vers la page d'accueil ou une autre page
            return redirect()->intended('/');
        } catch (\Exception $e) {
            // Enregistre l'erreur pour le débogage
            return redirect('/login')->with('error', 'Erreur lors de la connexion.');
        }
    }

}
