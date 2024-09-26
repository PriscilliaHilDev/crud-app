<?php

namespace App\Http\Controllers\Admin\Auth;

use Backpack\CRUD\app\Library\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminLoginController extends Controller
{
    protected ?string $loginPath = null;
    protected ?string $redirectTo = null;
    protected ?string $redirectAfterLogout = null;

    protected $data = []; // les informations envoyées à la vue

    // use AuthenticatesUsers;

    public function __construct()
    {
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard", ['except' => 'logout']);

        $this->loginPath ??= backpack_url('login');
        $this->redirectTo ??= backpack_url('dashboard');
        $this->redirectAfterLogout ??= backpack_url('login');
    }

    public function username()
    {
        return backpack_authentication_column();
    }

    /**
     * La méthode personnalisée pour déconnecter l'utilisateur Backpack.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Déconnecter l'utilisateur du guard Backpack
        $guard = backpack_auth();
    
        if ($guard->check()) {
            $guard->logout(); // Déconnexion de l'utilisateur du guard Backpack
        }
    
        // Rediriger vers la route souhaitée après la déconnexion
        return redirect()->route('post.list'); // Remplace 'post.list' par la route souhaitée
    }
    
    protected function guard()
    {
        return backpack_auth();
    }
}
