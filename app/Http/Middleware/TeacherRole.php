<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Controllers\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class TeacherRole extends Middleware{
    public function handle($request, Closure $next)
    {
    $user = Auth::user();

    if($user->role === 'teacher'){
        return $next($request);
    }
    
    return redirect()->back();
    }
}

