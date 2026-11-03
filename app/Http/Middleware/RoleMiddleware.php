<?php
namespace App\Http\Middleware;
use Closure;
class RoleMiddleware {
    public function handle($request, Closure $next, ...$roles) {
        if (!$request->user() || !in_array($request->user()->role, $roles, true)) {
            return response()->json(['message'=>'You are not authorized to perform this action.'], 403);
        }
        return $next($request);
    }
}
