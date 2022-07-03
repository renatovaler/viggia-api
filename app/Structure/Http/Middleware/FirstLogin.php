<?php

namespace App\Structure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FirstLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if(Route::currentRouteName() != 'users.myself.password.update' && Route::currentRouteName() != 'logout' && Route::currentRouteName() != 'login') {
            $guards = empty($guards) ? [null] : $guards;
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    $user = Auth::user();
                    if (
                        //!app('impersonate')->isImpersonating() &&
                        is_null($user->password_changed_at)
                    ) {
                        return response()->json([
                            'type' => 'error',
                            'message' => __('Unauthorized! This is the first login for this user and to proceed it is necessary to change the password for the first time.')
                        ], 403);
                    }
                }
            }
        }
        return $next($request);
    }
}
