<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;
<<<<<<< Updated upstream
=======
use Illuminate\Support\Facades\DB;

>>>>>>> Stashed changes

class ValimisedTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('server_time', now()->timestamp);
<<<<<<< Updated upstream
=======
        View::share('is_test_version', DB::table('properties')->where('property', 'test')->first()->value == 1);


>>>>>>> Stashed changes
        return $next($request);
    }
}
