<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfAdmin
{
    /**
     * Checked that the logged in user is an administrator.
     *
     * --------------
     * VERY IMPORTANT
     * --------------
     * If you have both regular users and admins inside the same table, change
     * the contents of this method to check that the logged in user
     * is an admin, and not a regular user.
     *
     * Additionally, in Laravel 7+, you should change app/Providers/RouteServiceProvider::HOME
     * which defines the route where a logged in user (but not admin) gets redirected
     * when trying to access an admin route. By default it's '/home' but Backpack
     * does not have a '/home' route, use something you've built for your users
     * (again - users, not admins).
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     * @return bool
     */
    private function checkIfUserIsAdmin($user)
    {
             return $user && $user->hasRole('admin'); // Vérifie si l'utilisateur a le rôle 'admin'

    }

    /**
     * Answer to unauthorized access request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private function respondToUnauthorizedRequest($request)
    {
        // if ($request->ajax() || $request->wantsJson()) {
    
        //     return response(trans('backpack::base.unauthorized'), 401);

        // } else {
        //     return redirect()->guest(backpack_url('login'));
        // }
        abort(404);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
     
        if( Auth::guard('web')->user()){

            $userAuthByWeb =  Auth::guard('web')->user();

            // si pas de connexion backpack et user web est admin
            if( backpack_auth()->guest() &&  $this->checkIfUserIsAdmin($userAuthByWeb))  {
                return redirect()->guest(backpack_url('login'));
            }
         
    
            if ( ! $this->checkIfUserIsAdmin($userAuthByWeb)) {
                abort(404);
            }
        }

        return $next($request);
    }
}
