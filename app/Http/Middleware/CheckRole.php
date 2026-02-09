<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        abort(403, 'Akses ditolak');
    }

    if (!in_array(auth()->user()->role, $roles)) {
        abort(403, 'Akses ditolak');
    }

    return $next($request);
}


}
