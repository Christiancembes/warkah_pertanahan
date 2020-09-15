<?php

namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Exception;

class authJWT
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                abort(400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                abort(400);
            }else{
                abort(401);
            }
        }
        return $next($request);
    }
}