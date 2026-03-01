<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (! $user || ! $user->role || ! in_array($user->role->name, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès refusé.'], Response::HTTP_FORBIDDEN);
            }
            abort(Response::HTTP_FORBIDDEN, 'Accès refusé.');
        }
        return $next($request);
    }
}
