<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthGoogleController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')
        ->with(['prompt' => 'consent']) // Ajoutez cette ligne
        ->redirect();    }

    public function handleProviderCallback()
    {
        $socialUser = Socialite::driver('google')->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            ['name' => $socialUser->getName(), 'password' => Str::random(24)]
        );
        Auth::login($user);
        return redirect('/dashboard'); // Redirige vers la page d'accueil ou tableau de bord
    }
}
