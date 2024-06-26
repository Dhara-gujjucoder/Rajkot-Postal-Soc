<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('locale')) {
            session()->put('locale', config('app.locale'));
        }
        ini_set('max_input_vars','50000');
        app()->setLocale(session('locale'));
        return $next($request);
    }
}
