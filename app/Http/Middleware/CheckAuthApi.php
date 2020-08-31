<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      
        if (!authApi()->user()) {

            $json['errors'][] = 'Your Are Not Authenticated';

            return response()->json($json, 401);
        }     

        return $next($request);
        
    }
}
