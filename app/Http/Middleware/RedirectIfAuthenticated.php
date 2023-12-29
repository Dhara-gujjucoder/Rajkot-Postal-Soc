<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // dd($guards);
        $guards = empty($guards) ? [null] : $guards;
        if (Auth::guard('web')->check()) {
            return redirect(RouteServiceProvider::HOME);
        }elseif(Auth::guard('users')->check()){
            return redirect()->route('user.home');
        }
        
        foreach ($guards as $guard) {
            
        }

        return $next($request);
    }
}
