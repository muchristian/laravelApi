<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
class JwtVerify extends BaseMiddleware
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
        
            try {

                JWTAuth::parseToken()->authenticate();
                
            } catch(Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json([
                        'error' => 'Provided token has expired'
                    ], Response::HTTP_UNAUTHORIZED);
                } 
                
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                    return response()->json([
                        'error' => 'Provided token is invalid'
                    ], Response::HTTP_UNAUTHORIZED);
                } 
    
                if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                    return response()->json([
                        'error' => 'No token found'
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }
            return $next($request);
        
    }
}
