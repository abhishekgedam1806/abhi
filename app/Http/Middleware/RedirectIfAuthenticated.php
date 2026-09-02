<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // If logged in as Job Seeker and explicitly requesting Business login/register tab
            if ($request->query('tab') === 'business' || $request->is('business-login') || $request->is('business-register')) {
                if (Auth::user()->isJobSeeker()) {
                    Auth::logout();
                    return $next($request);
                }
                return redirect()->route('business.dashboard');
            }

            return redirect('/home');
        }
        return $next($request);
    }
}
