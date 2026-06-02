<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    $user = Auth::user();

    if ($user && ($user->role === 'admin' || ($user->role ?? '') === 'admin')) {
        return $next($request);
    }

    return redirect()->route('login');
}

}

