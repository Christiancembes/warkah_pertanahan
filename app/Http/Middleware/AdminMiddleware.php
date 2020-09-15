<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class AdminMiddleware
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
        $user = JWTAuth::parseToken()->authenticate();
        
        if ($user->type != 'admin') abort(401);

        return $next($request);
    }
}
