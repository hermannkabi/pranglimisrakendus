<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->role == "guest" || $request->user()->email_verified_at == null){
            return redirect()->route("valimised.notverified");
        }

        //Todo: application for voting rights was denied

        if(Application::where('applicant', $request->user()->id)->whereIn('application_type', ['valimised-basic', 'valimised-vip', 'valimised-twofox'])->where('status', 'granted')->exists() || str_contains($request->user()->role, "valimised-admin")){
            return $next($request);
        }else{
            return redirect()->route("valimised.apply");
        }

    }
}
