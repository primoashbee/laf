<?php

namespace App\Http\Middleware;

use Closure;

class AccountAble
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->disabled == 1){
            auth()->logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
