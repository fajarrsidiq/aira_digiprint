<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, ...$levels)
    {
        if (!Auth::guard('petugas')->check()) return redirect('login');
        $user = Auth::guard('petugas')->user();
        if (!in_array($user->level, $levels)) abort(403);
        return $next($request);
    }
}
