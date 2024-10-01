<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $roles = $user ? $user->roles->pluck('name') : []; // Récupérer les rôles de l'utilisateur

        return view('app', compact('roles')); // Passer les rôles à la vue
    }
}