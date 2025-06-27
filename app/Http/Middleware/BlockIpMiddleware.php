<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIpMiddleware
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
        if (in_array("admin", explode(",", $request->user()->role)))
        {
            return $next($request);
        }
        if (!in_array($request->ip(), env('ALLOWED_ADMIN_IP') ?? "")) {
            return response()->json([
              'message' => "You don't have permission to access this website."
            ], 401); //TODO: Lisa error message.
        }
        
        return $next($request);
    }
}
