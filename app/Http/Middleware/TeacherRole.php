<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Controllers\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class TeacherRole extends Middleware{
    public function handle($request, Closure $next)
    {
    $user = Auth::user();

    if($user->role == 1)
        return $next($request);

    return redirect('login');
    }
}

