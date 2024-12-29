<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
class Authenticate
{
    /**
     * Determine if the user is authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401); // Повертаємо 401 для API запитів
            }

            return response()->json(['message' => 'Unauthorized'], 401); // Повертаємо 401 для API запитів
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
}
