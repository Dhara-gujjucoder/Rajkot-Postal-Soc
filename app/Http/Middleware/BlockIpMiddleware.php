<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIpMiddleware
{

    public $whiteIps = ['192.168.0.169','192.168.0.114','192.168.0.163','192.168.0.1','192.168.0.177','103.105.233.194'];
    //public $whiteIps = ['103.105.233.194','103.81.116.102'];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->ip(), $this->whiteIps)) {
            return $next($request);
        }elseif(request()->is('home')){
            return $next($request);
        }
        return redirect()->route('front.home');
    }
}
