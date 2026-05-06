<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    public function handle(Request $request, Closure $next)
    { 
        if (Auth::check()){
            return redirect()->route('account.profile');

        }
        return $next($request);
    }
}
