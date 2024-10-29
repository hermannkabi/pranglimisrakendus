<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if(count(array_intersect(explode(";", $role), explode(",", $request->user()->role))) <= 0){
            abort(403);
        }

        if($role == "teacher" && !$request->user()->email_verified_at){
            abort(404, "Selleks tegevuseks pead oma e-posti kinnitama!");
        }

        return $next($request);
    }
}
