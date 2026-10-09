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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin has access to everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        if (!$user->hasRole($roles)) {
            abort(403, 'Unauthorized action: Your role does not have permission to access this resource.');
        }

        return $next($request);
    }
}
