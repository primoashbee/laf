<?php

namespace App\Http\Middleware;

use Closure;

class PasswordChanged
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
        
        if(auth()->user()->password_changed == false){
            return redirect()->route('change.password');
        }
        return $next($request);
    }
}
